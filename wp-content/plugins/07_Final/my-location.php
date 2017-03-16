<?php
/*
Plugin Name: my-location
Description: 장소 기록 플러그인 완전 예제. 단, 구글맵 API 키는 별도로 발급받아야 합니다. <a href="https://cloud.google.com/console/project">Google Console</a>에서 발급받으세요.
*/

/** 커스텀 포스트를 등록합니다. */
add_action( 'init', 'my_loc_register_post_type' );

/**
 * action: init
 */
function my_loc_register_post_type() {

	register_taxonomy(
		'place-category',
		array( 'my-location' ),
		array(
			'labels' => array(
				'name'  => _x( 'Place categories', 'category type general name', 'my-location' ),
			),
			'hierarchical' => TRUE,
		)
	);

	$labels = array(
		'name'                              => _x( 'My Locations', 'post type general name', 'my-location' ),
		'singular_name'                     => _x( 'My Location',  'post_type singular name', 'my-location' ),
		'menu_name'                         => _x( 'My Locations', 'admin menu name', 'my-location' ),
		'name_admin_bar'                    => _x( 'My Location',  'add new on admin bar', 'my-location' ),
		'all_items'                         => __( 'All Locations', 'my-location' ),
		'add_new'                           => _x( 'Add New', 'book', 'my-location' ),
		'add_new_item'                      => __( 'Add New Location', 'my-location' ),
		'edit_item'                         => __( 'Edit Location', 'my-location' ),
		'new_item'                          => __( 'New Location', 'my-location' ),
		'view_item'                         => __( 'View Location', 'my-location' ),
		'search_items'                      => __( 'Search Locations', 'my-location' ),
		'not_found'                         => __( 'No location found.', 'my-location' ),
		'not_found_in_trash'                => __( 'No location found in Trash.', 'my-location' ),
		'parent_item_colon'                 => __( 'Parent Locations:', 'my-location' ),
	);

	$args = array(
		'label'                             => __( 'My Locations', 'my-location' ),
		'labels'                            => $labels,
		'public'                            => TRUE,
		'menu_icon'                         => 'dashicons-location-alt',
		'register_meta_box_cb'              => 'my_loc_meta_box',
		'supports'                          => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'                        => array( 'place-category' )
	);

	register_post_type( 'my-location', $args );
}

/** 그냥 메뉴도 하나 넣어 봅니다. 'my-location' UI 구조의 서브메뉴에 집어 넣습니다. */
add_action( 'admin_menu', 'my_loc_add_admin_menu' );

/**
 * action: admin_menu
 */
function my_loc_add_admin_menu() {

	$menu_hook = add_submenu_page(
		'edit.php?post_type=my-location',
		_x( 'My Locations', 'submenu page title', 'my-location' ),
		_x( 'My Locations', 'submenu_page label', 'my-location' ),
		'manage_options',
		'my_location_submenu',
		'__return_empty_string'
	);
	add_action( 'load-' . $menu_hook, 'my_loc_add_help_tab' );
}

/**
 * 도움말 항목
 */
add_action( 'load-edit.php', 'my_loc_add_help_tab' );

/**
 * action: load-edit.php
 */
function my_loc_add_help_tab() {

	global $typenow;

	if( $typenow == 'my-location' ) {
		$screen = get_current_screen();
		$screen->add_help_tab(
			array(
				'id'      => 'my-location-help-tab',
				'title'   => __( 'My Location Help', 'my-location' ),
				'content' => '<p>' . __( "여기는 도움말 표시 영역입니다.", 'my-location' ) . '</p>'
			)
		);
		$screen->set_help_sidebar( "more info!" );
	}
}

/**
 * caller: my_loc_register_post_type (action init)
 * 커스텀 포스트의 메타 박스입니다.
 *
 * @param \WP_Post $post
 */
function my_loc_meta_box( $post ) {

	add_meta_box( 'my-location-map', __( 'Location property', 'my-location' ), function() use ( $post ) {
		wp_enqueue_script( 'my-location-script-handle' );
		$lat_lng = get_post_meta( $post->ID, 'my-location-latlng', TRUE );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$lng = $lat = '0';
		if( isset( $lat_lng['lat'] ) && isset( $lat_lng['lng'] ) ) {
			$lat = esc_attr( $lat_lng['lat'] );
			$lng = esc_attr( $lat_lng['lng'] );
		}
		/** @noinspection PhpUnusedLocalVariableInspection */
		$address = esc_attr( get_post_meta( $post->ID, 'my-location-address', TRUE ) );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$rating = esc_attr( get_post_meta( $post->ID, 'my-location-rating', TRUE ) );
		include( 'map-meta-box.php' );
	} );
}

/** 스크립트 (css/js)를 미리 등록하는 훅입니다. 어드민 화면에서만 동작합니다.*/
add_action( 'admin_enqueue_scripts', 'my_loc_enqueue_scripts' );

/** action: admin_enqueue_scripts */
function my_loc_enqueue_scripts() {
	$api_key = 'AIzaSyDRxm9pF0C8AHQKvGSVEdmIYgVFSpIknaE';   // 구글맵 API 키
	wp_register_script( 'google-map-handle', "https://maps.googleapis.com/maps/api/js?key={$api_key}&sensor=false" );
	wp_register_script( 'my-location-script-handle', plugins_url( 'statics/js/my-location.js' , __FILE__  ), array( 'jquery', 'google-map-handle' ), NULL, FALSE );
	// wp_add_inline_style( 'admin-menu', 'input#post-query-submit {display: none;}' );
}

/** 포스트 저장 전 필요한 행동을 추가적으로 정의하기 위한 훅입니다. */
add_action( 'save_post', 'my_loc_save_meta_box', 10, 2 );

/**
 * action: save_post
 *
 * @param integer $post_id
 */
function my_loc_save_meta_box( $post_id, $post ) {

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if( !wp_verify_nonce( $_POST['my_location_map_nonce'], 'my_location_map' )  ||
	    !wp_verify_nonce( $_POST['my_location_address_nonce'], 'my_location_address' ) ||
	    !wp_verify_nonce( $_POST['my_location_rating_nonce'], 'my_location_rating' )
	) {
		return; // 인증 실패했다고 무조건 수상한 침입은 아니다. (e.g. 일괄 작업, ...)
	}

	// Check the user's permissions.
	if ( 'my-location' == $post->post_type ) {
		$caps = map_meta_cap( 'edit_post', get_current_user_id(), $post_id );
		if( empty( $caps ) ) {
			die( 'caps is empty' );
		}
		foreach( $caps as &$cap ) {
			if ( ! current_user_can( $cap ) ) {
				die('You cannot do that: '. $cap);
			}
		}
	}

	$data = array(
		'lat' => 0.0,
		'lng' => 0.0,
	);

	if( isset( $_POST['my-location-lat'] ) && isset( $_POST['my-location-lng'] ) ) {
		$data['lat'] = floatval( $_POST['my-location-lat'] );
		$data['lng'] = floatval( $_POST['my-location-lng'] );
	}
	update_post_meta( $post_id, 'my-location-latlng', $data );

	$address = '';
	if( isset( $_POST['my-location-address']) ) {
		$address = sanitize_text_field( $_POST['my-location-address'] );
	}
	update_post_meta( $post_id, 'my-location-address', $address );

	$rating = '0.0';
	if( isset( $_POST['my-location-rating'] ) ) {
		$rating = sprintf("%.01f", floatval( $_POST['my-location-rating'] ) );
	}
	update_post_meta( $post_id, 'my-location-rating', $rating );
}

/**
 * my-location 포스트 목록 테이블의 헤더 컬럼 변경
 */
add_filter( 'manage_my-location_posts_columns', 'my_loc_custom_columns' );

/**
 * filter: manage_my-location_posts_columns
 *
 * @param  array $columns
 * @return array
 */
function my_loc_custom_columns( $columns ) {

	$columns['position'] = __( 'Position', 'my-location' );
	$columns['address']  = __( 'Address', 'my-location' );
	$columns['rating']   = __( 'Rating', 'my-location' );
	return $columns;
}

/**
 * my-location 포스트 목폭 테이블 중 일부 테이블의 헤더 컬럼을 정렬 가능하게 변경
 */
add_filter( 'manage_edit-my-location_sortable_columns', 'my_loc_custom_sortable_columns' );

/**
 * filter: manage_edit-my-location_sortable_columns
 * @param array $columns
 *
 * @return array
 */
function my_loc_custom_sortable_columns( $columns ) {

	$columns['address'] =  __( 'Address', 'my-location' );
	$columns['rating']  = __( 'Rating', 'my-location' );
	return $columns;
}

/**
 * my-location 포스트 목록 테이블 커스텀 필드 정보를 출력하는 역할
 */
add_action( 'manage_my-location_posts_custom_column', 'my_loc_edit_custom_column', 10, 2);

/**
 * action: manage_my-location_posts_custom_column
 * @param $column
 * @param $post_id
 */
function my_loc_edit_custom_column( $column, $post_id ) {

	switch( $column ) {
		case 'position':
			$lat_lng = get_post_meta( $post_id, 'my-location-latlng', TRUE );
			$lat = esc_attr( sprintf( "%.03f", $lat_lng['lat'] ) );
			$lng = esc_attr( sprintf( "%.03f", $lat_lng['lng'] ) );
			echo "Lat: $lat / Lng: $lng";
			break;

		case 'address':
			$address = esc_attr( get_post_meta( $post_id, 'my-location-address', TRUE ) );
			echo $address;
			break;

		case 'rating':
			$rating = esc_attr( get_post_meta( $post_id, 'my-location-rating', TRUE ) );
			echo $rating;
			break;
	}
}

/**
 * my-location 포스트 목록을 가져 오기 전에 미리 쿼리 조정
 */
add_action( 'pre_get_posts', 'my_loc_pre_get_posts' );

/**
 * action: pre_get_posts
 * @param \WP_Query $query
 */
function my_loc_pre_get_posts( $query ) {

	// 어드민 "페이지"가 아니면 리턴.
	if ( ! is_admin() ) {
		return;
	}

	// 주소와 평점을 정렬 가능하게 만들었는데, 주소는 주소의 메타 값을 문자열로,
	// 평점은 평점의 메타 값을 *숫자*로 정렬해야 한다.
	$order_by = $query->get('orderby');
	switch( $order_by ) {

		case 'Address':
			$query->set( 'meta_key', 'my-location-address' );
			$query->set( 'orderby', 'meta_value' );
			break;

		case 'Rating':
			$query->set( 'meta_key', 'my-location-rating' );
			$query->set( 'orderby', 'meta_value_num' );
			break;
	}

	// my-location UI 에서 전체 내용 검색을 하는 경우: ** 매우 중요한 소스 코드 **
	global $pagenow;
	$post_type = $query->get('post_type');
	$term      = esc_attr( $query->get('s') );
	// my-location UI 편집 창에서만 동작
	if( $post_type == 'my-location' && $pagenow == 'edit.php' && !empty( $term ) ) {

		$meta_query = $query->get( 'meta_query' );
		if( empty( $meta_query ) ) {
			$meta_query = array( 'relation' => 'OR' );
		} else {
			$meta_query['relation'] = 'OR';
		}

		$search = array( 'relation' => 'OR', );
		$meta_keys = array( 'my-location-address', ); // 이 부분에서 메타 키를 추가할 수도 있음.
		foreach( $meta_keys as $meta_key ) {
			$search[] = array(
				'key'     => $meta_key,
				'value'   => $term,
				'compare' => 'LIKE'
			);
		}
		$meta_query[] = $search;
		$query->set( 'meta_query',  $meta_query );

		// WHERE 절의 meta_key / meta_value 를 검색하는 부분이 보통은 AND 이고
		// 이것이 거의 모든 경우 합리적인 논리 연산으로 작동하지만,
		// 여기서는 내용 검색을 해야 하는 거고, 검색 질의어 결과는 포스트 제목, 본문, 그리고 내가 정한 어떤 커스텀 필드에서
		// AND 로직이 아닌 OR 로직의 결과여야 (어떤 거 하나라도 검색되면 모두 나오게) 한다.
		add_filter( 'get_meta_sql', function( $sql ) {
			$sql['where'] = preg_replace( '/\s*AND\s+(.+)/ms', ' OR $1', $sql['where'] );
			return $sql;
		});
	}
}

/**
 * 뷰 ( 테이블 위 모두 (x) | 발행됨 (y) | .... ) 출력 부분 수정
 */
add_filter( 'views_edit-my-location', 'my_loc_views_edit' );

/**
 * filter: views_edit-my-location
 *
 * @param $views array
 *
 * @return array
 */
function my_loc_views_edit( $views ) {

	$args = array(
		'post_type'        => 'my-location',
		'post_status'      => 'publish',
		'meta_query'       => array(
			array(
				'key'     => 'my-location-rating',
				'value'   => NULL,
				'type'    => 'NUMERIC',
				'compare' => 'BETWEEN',
			),
		),
		'cache_results'          => FALSE,
		'update_post_meta_cache' => FALSE,
	);

	$admin_url = admin_url('edit.php');
	$format    = '<a href="%s?post_type=my-location&view=%s">%s (%d)</a>';

	// parse_query 시 걸리는 콜백을 제거하지 않으면
	// 아래 WP_Query 생성 시 의도치 않은 콜백이 동작하게 된다.
	if( has_action( 'parse_query', 'my_loc_parse_query' ) ) {
		remove_action( 'parse_query', 'my_loc_parse_query' );
		remove_action( 'parse_tax_query', 'my_loc_tax_query_edit' );
	}

	$args['meta_query'][0]['value'] = array(7.5, 10);
	$hi = new WP_Query( $args );
	$views['rating-hi']  = sprintf( $format, $admin_url, 'rating-hi',  __( 'high rated', 'my-location' ), $hi->found_posts );

	$args['meta_query'][0]['value'] = array(4.0, 7.0);
	$mid = new WP_Query( $args );
	$views['rating-mid'] = sprintf( $format, $admin_url, 'rating-mid', __( 'medium rated', 'my-location' ), $mid->found_posts );

	$args['meta_query'][0]['value'] = array(0, 3.5);
	$low = new WP_Query( $args );
	$views['rating-low'] = sprintf( $format, $admin_url, 'rating-low', __( 'low rated', 'my-location' ), $low->found_posts );

	add_action( 'parse_query', 'my_loc_parse_query' );

	return $views;
}

/**
 * 쿼리 파싱 규칙 추가를 위한 액션
 */
add_action( 'parse_query', 'my_loc_parse_query' );

/**
 * action: parse_query
 *
 * @param $query \WP_Query
 */
function my_loc_parse_query( $query ) {

	global $pagenow;

	if( $pagenow == 'edit.php' &&
	    isset( $_GET['post_type'] ) && $_GET['post_type'] == 'my-location' &&
	    isset( $_GET['view'] ) ) {

		$args = array(
			array(
				'key'     => 'my-location-rating',
				'value'   => NULL,
				'type'    => 'NUMERIC',
				'compare' => 'BETWEEN',
			),
		);
		switch( $_GET['view'] ) {
			case 'rating-hi':
				$args[0]['value'] = array(7.5, 10);
				break;

			case 'rating-mid':
				$args[0]['value'] = array(4.0, 7.0);
				break;

			case 'rating-low':
				$args[0]['value'] = array(0, 3.5);
				break;
		}

		$query->set( 'post_status', 'publish' );
		$query->set( 'meta_query', $args );
	}
}

/**
 * 필터 영역의 컨트롤 추가.
 */
add_action( 'restrict_manage_posts', 'my_loc_restrict_manage_posts' );

/**
 * action: restrict_manage_posts
 */
function my_loc_restrict_manage_posts() {

	if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'my-location' ) {

		$term = get_term_by( 'slug', $_GET['place-category'], 'place-category' );

		wp_dropdown_categories(
			array(
				'show_option_all'   => __( 'All places', 'my-location' ),
				'taxonomy'          => 'place-category',
				'show_count'        => 1,
				'selected'          => $term->slug,
				'hierarchical'      => TRUE,
				'name'              => 'place-category',
				'value_field'       => 'slug',
			)
		);
	}
}

/**
 * 각 열의 첫 행에 나오는 액션 내용을 수정합니다.
 */
add_filter( 'post_row_actions', 'my_loc_post_row_action', 10, 2 );

/**
 * filter: post_row_actions
 *
 * @param $actions
 * @param $post
 *
 * @return mixed
 */
function my_loc_post_row_action(  $actions, $post ) {

	global $typenow;
	if( $typenow == 'my-location' ) {
		$actions['user'] = sprintf(
			'<a href="%s?user_id=%s">%s</a>',
			admin_url( 'profile.php' ),
			$post->post_author,
			__( '쓴 사람은 누구?', 'my-location' )
		);
	}

	return $actions;
}

/**
 * 일괄 작업 부분을 수정합니다.
 */
// add_filter( 'bulk_actions-edit-my-location', function() { return array(); }, 10, 0 );

/**
 * 모든 날짜 필터 부분을 수정합니다.
 */
// add_filter( 'months_dropdown_results', function() { return array(); }, 10, 0 );

