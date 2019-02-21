<?php
  /*
  Plugin Name: Loxhome Calculator (WIP)
  Plugin URI: 
  description: a calculator for Loxhome
  Version: 1.0
  Author: Bernhard Steger
  Author URI: https://berneau.at
  License: GPL2
  */
  
  if (!defined('LXHM_PLUGIN_NAME'))
      define('LXHM_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

  if (!defined('LXHM_PLUGIN_DIR'))
      define('LXHM_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . LXHM_PLUGIN_NAME);

  if (!defined('LXHM_PLUGIN_URL'))
      define('LXHM_PLUGIN_URL', WP_PLUGIN_URL . '/' . LXHM_PLUGIN_NAME);

  add_action( 'wp_enqueue_scripts', 'lxhm_enqueue_scripts' );

  function lxhm_enqueue_scripts(){
    wp_register_script( 'ajaxHandle', plugins_url('lxhm-ajax.js', __FILE__), array(), false, true );
    wp_enqueue_script( 'ajaxHandle' );
    wp_localize_script( 'ajaxHandle', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    
    wp_enqueue_script( 'lxhm-script', LXHM_PLUGIN_URL . '/lxhm-calculator.js', array(), false, true );
    wp_enqueue_style( 'lxhm-style', LXHM_PLUGIN_URL . '/lxhm-calculator.css', array(), null );
    // wp_enqueue_style( 'lxhm-icon-font', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), null);
  }
  
  require_once LXHM_PLUGIN_DIR . '/shortcode.php';
  require_once LXHM_PLUGIN_DIR . '/ajax/add-room.php';
  require_once LXHM_PLUGIN_DIR . '/ajax/add-article.php';
  require_once LXHM_PLUGIN_DIR . '/ajax/calculate-rooms.php';
  require_once LXHM_PLUGIN_DIR . '/ajax/get-options.php';
  require_once LXHM_PLUGIN_DIR . '/ajax/add-to-cart.php';
?>