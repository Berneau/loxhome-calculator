<?php
add_action( "wp_ajax_lxhm_add_room", "lxhm_add_room" );
add_action( "wp_ajax_nopriv_lxhm_add_room", "lxhm_add_room" );

add_action( "wp_ajax_lxhm_add_area", "lxhm_add_area" );
add_action( "wp_ajax_nopriv_lxhm_add_area", "lxhm_add_area" );

add_action( "wp_ajax_lxhm_get_options", "lxhm_get_options" );
add_action( "wp_ajax_nopriv_lxhm_get_options", "lxhm_get_options" );

add_action( "wp_ajax_lxhm_get_tooltips", "lxhm_get_tooltips" );
add_action( "wp_ajax_nopriv_lxhm_get_tooltips", "lxhm_get_tooltips" );

add_action( "wp_ajax_lxhm_calculate_rooms", "lxhm_calculate_rooms" );
add_action( "wp_ajax_nopriv_lxhm_calculate_rooms", "lxhm_calculate_rooms" );

add_action( "wp_ajax_lxhm_add_to_cart", "lxhm_add_to_cart" );
add_action( "wp_ajax_nopriv_lxhm_add_to_cart", "lxhm_add_to_cart" );
?>