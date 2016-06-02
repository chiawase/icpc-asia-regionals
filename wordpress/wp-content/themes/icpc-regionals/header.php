<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?php bloginfo('name'); ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://use.fontawesome.com/f452a9cbf5.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
  <![endif]-->

  <header>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo" title="<?php bloginfo('name'); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/img/icpc-logo.png">
    </a>

    <?php if (has_nav_menu('primary')): ?>
    <label for="show-menu" class="show-menu"><i class="fa fa-bars"></i></label>
    <input type="checkbox" id="show-menu" role="button">
    <nav role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'icpc-regionals' ); ?>">
      <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class' => 'primary-menu',
          ));
      ?>
    <?php endif; ?>
      <!-- <a class="nav-item" href="<?php echo esc_url(home_url('/')); ?>">Home</a> -->
      <!-- <a class="nav-item" href="post.html">About</a> -->
      <!-- <a class="nav-item" href="post.html">Schedule</a> -->
      <!-- <a class="nav-item" href="post.html">Instructions</a> -->
      <!-- <a class="nav-item" href="post.html">News &amp; Announcements</a> -->
      <!-- <a id="register" class="nav-item" href="register.html">How to Register</a> -->
    </nav>
  </header>