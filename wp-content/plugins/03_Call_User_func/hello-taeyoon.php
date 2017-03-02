<?php
function hello_world( $value ) {
	echo "Hello, $value !\n";
}
hello_world( 'taeyoon' );
call_user_func( 'hello_world', 'callback' );
call_user_func_array( 'hello_world', array( 'World' ) );
