<?php
/**
 * ICPC Regionals Theme functions
 */

if (! function_exists('icpcregionals_setup')):
  function icpcregionals_setup() {
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    register_nav_menus( array (
      'primary' => __('Primary Menu', 'icpc-regionals'),
    ));

    add_theme_support( 'html5', array(
      'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ));
  }
endif;
add_action('after_setup_theme', 'icpcregionals_setup');