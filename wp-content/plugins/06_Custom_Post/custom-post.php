<?php
/*
Plugin Name: 커스텀 포스트 예제
Author: changwoo
Author URI: mailto://cs.chwnam@gmail.com
Description: 커스텀 포스트의 모든 키워드를 살펴 보는 예제입니다.
*/

$music_collection_obj = NULL;

add_action( 'init', 'add_music_collection_custom_post' );

function add_music_collection_custom_post() {

	$labels = array(
		'name'               => 'Music Collections',
		'singular_name'      => 'Music Collection',
		'menu_name'          => 'Music Collection',
		'name_admin_bar'     => 'Music Collection',
		'all_items'          => 'Music Collection',
		'add_new'            => 'Add Collection',
		'add_new_item'       => 'Let\'s Add Collection',
		'edit_item'          => 'Edit Collection',
		'view_item'          => 'View Collection',
		'search_items'       => 'Search Collection',
		'parent_item_colon'  => 'Collection Parent',
		'not_found'          => 'Collection not found',
		'not_found_in_trash' => 'No collection in trash',
	);

	$args = array(

		/*********************************************************************
		 *********************************************************************
		 *********************************************************************
		 *  Exposition
		 *********************************************************************
		 *********************************************************************
		 *********************************************************************/

		// 공개 포스트: 아래 값들의 대표입니다.
		'public'                      => TRUE,


		// 프론트에서 검색이 가능합니다.
		'exclude_from_search'         => FALSE,

		// URL에 이 포스트와 관련된 쿼리를 쓸 수 있는지를 결정합니다.
		'publicly_queryable'          => TRUE,

		// UI를 보여줄지 결정합니다. 아래 요소들과 관련 있습니다.
		'show_ui'                     => TRUE,

		// 외모 - 메뉴에서 이 포스트가 노출됩니다.
		'show_in_nav_menus'           => FALSE,

		// 관리자 화면에서 메뉴가 나옵니다.
		'show_in_menu'                => TRUE,

		// 관리자 상단 바에서 메뉴가 나옵니다.
		'show_in_admin_bar'           => FALSE,





		/*********************************************************************
		 *********************************************************************
		 *********************************************************************
		 *  Visual Queue
		 *********************************************************************
		 *********************************************************************
		 *********************************************************************/

		// 메뉴 레이블을 표시합니다. 복수형이 기본입니다.
		'label'                       => 'Music Collection',

		// 각 메뉴에 대해 보다 자세한 텍스트를 지정할 수 있습니다.
		// 'labels'                      => $labels,

		/** 메뉴 위치를 조정할 수 있습니다.
		5 - below Posts
		10 - below Media
		15 - below Links
		20 - below Pages
		25 - below comments
		60 - below first separator
		65 - below Plugins
		70 - below Users
		75 - below Tools
		80 - below Settings
		100 - below second separator
		NULL - below comments */
		'menu_postion'                => NULL,

		/**
		 * 아이콘을 지정할 수 있습니다.
		 * @see https://developer.wordpress.org/resource/dashicons
		 * default: NULL
		 */
		'menu_icon'                   => 'dashicons-visibility',





		/*********************************************************************
		 *********************************************************************
		 *********************************************************************
		 * Resource Location
		 *********************************************************************
		 *********************************************************************
		 *********************************************************************/
		'permalink_epmask'            => EP_PERMALINK,

		'rewrite'                     => array(
			'slug'         => 'mc',
			'with_front'   => TRUE,
			// feed 주소를 rewrite 합니다. has_archive 가 우선 TRUE 여야 합니다.
			'feeds'        => TRUE,
			'pages'        => TRUE,
			'ep_mask'      => EP_PERMALINK,
		),

		// 쿼리 변수 이름을 다르게 할 수 있습니다.
		// FALSE라면 /?{query_var}={single_post_slug} 를 쓸 수 없습니다.
		// 또 'publicly_queryable'이 FALSE면 이 값은 무의미하다는 점 주의하세요.
		'query_var'                   => 'mcq',





		/*********************************************************************
		 *********************************************************************
		 *********************************************************************
		 *  Access Control
		 *********************************************************************
		 *********************************************************************
		 *********************************************************************/
		 'capability_type'             => array( 'music_collection', 'music_collections' ),
		 
		 'capabilites'                 => array(
        'edit_post'          => 'edit_music_collection', 
        'read_post'          => 'read_music_collection', 
        'delete_post'        => 'delete_music_collection', 
        'edit_posts'         => 'edit_music_collections', 
        'edit_others_posts'  => 'edit_others_music_collections', 
        'publish_posts'      => 'publish_music_collections',       
        'read_private_posts' => 'read_private_music_collections', 
        'create_posts'       => 'edit_music_collections', 
      ),
     
     'map_meta_cap'                => NULL,





		/*********************************************************************
		 *********************************************************************
		 *********************************************************************
		 * Content Characteristics
		 *********************************************************************
		 *********************************************************************
		 *********************************************************************/
		'hierarchical'                => FALSE,
		'supports'                    => array(
			'title',
			'editor',
			// 'author',
			// 'thumbnail',
			// 'excerpt',
			// 'trackbacks',
			// 'custom-fields',
			// 'comments',
			// 'revisions',
			// 'page-attributes',
			// 'post-formats'
		),
		'register_meta_box_cb'        => 'add_music_collection_meta_box',
		'taxonomies'                  => array( 'music_genre_category' ),
		'has_archive'                 => FALSE,




		/*********************************************************************
		 *********************************************************************
		 *********************************************************************
		 * ETC
		 *********************************************************************
		 *********************************************************************
		 *********************************************************************/
		'description'                 => 'Music collection: a custom post example',
		'can_export'                  => TRUE,
	);

	global $music_collection_obj;
	$music_collection_obj = register_post_type( 'music_collection', $args );
}


add_action( 'admin_menu', 'music_collection_add_admin_menu' );
function music_collection_add_admin_menu() {

	add_submenu_page(
		'edit.php?post_type=music_collection',
		'포스트 디버그',
		'포스트 디버그',
		'read',
		'music_collection_submenu',
		function() {
			global $music_collection_obj;
			echo "<pre>";
			print_r( $music_collection_obj );
			echo "</pre>";
		}
	);
}

function add_music_collection_meta_box() {

	add_meta_box( 'my_meta_box', 'my meta box', 'my_meta_box_callback', 'music_collection', 'normal', 'default', array() );
}

function my_meta_box_callback() {
	echo "hello";
}

add_action( 'init', function() {
  register_taxonomy(
    'music_genre_category',
    'music_collection',
    array( 'label' => '카테고리', 'hierarchical'=>true )
  );
} );

