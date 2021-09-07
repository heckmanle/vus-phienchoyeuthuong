<?php
/**
 * The header for our theme
 *
 * @since 1.0
 * @version 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon.png?v=1.2"/>
    <link rel="stylesheet" href="<?php echo THEME_URL; ?>/assets/cfonts/fonts.css?ver=2" media="all" />
    <?php wp_head(); ?>

</head>
<body <?php body_class('light sidebar-mini sidebar-collapse'); ?>>
    <div id="wrapper">

