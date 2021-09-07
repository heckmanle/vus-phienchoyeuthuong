<?php
/**
 * The header for our theme
 *
 * @since 1.0
 * @version 1.0
 */

use SME\Includes\Core\User;
global $system_api, $currentUser;
if( isset($_REQUEST['action']) && 'logout' === $_REQUEST['action'] ){
    $system_api->clear_token_cookie();
}
$currentUser = User::get_current();
///////////////////
$user_management = [];
$link_profile = site_url();
if( defined('USERSMANAGEMENT_LANG_DOMAIN') ) {
    $user_management = get_page_by_path(__('Usersmanagement', USERSMANAGEMENT_LANG_DOMAIN));
    if( !empty($user_management) && $user_management instanceof \WP_Post) {
        $link_profile = add_query_arg(['view' => 'profile', 'uid' => $currentUser['id']], get_the_permalink($user_management));
    }
}
$user_avatar = !empty($currentUser['avatar']) ? $currentUser['avatar'] : (defined('USERSMANAGEMENT_MODULE_URL') ? USERSMANAGEMENT_MODULE_URL . 'images/default.jpg' : '');

$roles = $currentUser['roles'];
$roles = array_shift($roles);
$role_name = mb_strtoupper($roles['role_name']);
if ( ! defined( 'WPINC' ) ) { die; }
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">

    <?php
    elegant_description();
    elegant_keywords();
    elegant_canonical();

    do_action( 'et_head_meta' );

    $template_directory_uri = get_template_directory_uri();
    ?>

    <link rel="stylesheet" href="<?php echo THEME_URL; ?>/assets/cfonts/fonts.css?ver=2" media="all" />
    <link rel="shortcut icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon.png?v=1.2"/>
    <script>
        const IMAGE_LOADING = {
            message: '<div class="ball-clip-rotate-multiple"> <div></div><div></div></div>',
            css: {border: '', padding: 'none', width: '40px', height: '40px'}

        };
        const AJAX_URL = "<?php echo admin_url('admin-ajax.php'); ?>",
            THOUNSAND_SEP = "<?php echo THOUNSAND_SEP; ?>",
            NUM_DECIMAL = "<?php echo NUM_DECIMAL; ?>",
            DECIMAL_SEP = "<?php echo DECIMAL_SEP; ?>";

        const MESSAGES_VALIDATORS = {
            required: "<?= __("Trường này không được để trống."); ?>",
            remote: "<?= __("Vui lòng sửa trường này."); ?>",
            email: "<?= __("Vui lòng nhập email đúng định dạng."); ?>",
            url: "<?= __("Vui lòng nhận đường dẫn đúng định dạng."); ?>",
            date: "<?= __("Vui lòng nhập ngày tháng đúng định dạng."); ?>",
            dateISO: "<?= __("Vui lòng nhập ngày tháng đúng định dạng (ISO)."); ?>",
            number: "<?= __("Vui lòng nhập một số hợp lệ."); ?>",
            digits: "<?= __("Vui lòng chỉ nhập các chữ số."); ?>",
            equalTo: "<?= __("Vui lòng nhập lại cùng một giá trị."); ?>",
            maxlength: "<?= __("Vui lòng nhập không quá {0} ký tự."); ?>",
            minlength: "<?= __("Vui lòng nhập ít nhất {0} ký tự."); ?>",
            rangelength: "<?= __("Vui lòng nhập một giá trị trong khoảng từ {0} đến {1} ký tự."); ?>",
            range: "<?= __("Vui lòng nhập giá trị trong khoảng từ {0} đến {1}."); ?>",
            max: "<?= __("Vui lòng nhập một giá trị nhỏ hơn hoặc bằng {0}."); ?>",
            min: "<?= __("Vui lòng nhập một giá trị lớn hơn hoặc bằng {0}."); ?>",
            step: "<?= __("Vui lòng nhập một bội số của {0}."); ?>"
        }
    </script>

    <script type="text/javascript">
        document.documentElement.className = 'js';
    </script>

    <?php wp_head(); ?>
    <?php
    wp_enqueue_style( 'select-tow-public-css', THEME_URL . '/assets/libs/select2/css/select2.css', array(), '', 'all' );
    ?>
    <?php $lang="";
    if(isset($_GET['lang']) && $_GET['lang'] == "vi") {
        ?>
        <link rel="stylesheet" href="<?php echo $template_directory_uri; ?>/vi-style.css?ver=2" media="all" />
        <?php
    }
    ?>
    <?php
    wp_enqueue_style( 'theme-style', THEME_URL . '/style.css', array(), '', 'all' );
    wp_enqueue_style( 'guest-style', THEME_URL . '/guest/styles.css', array(), '', 'all' );
    ?>
</head>
<body <?php body_class('light sidebar-mini sidebar-collapse'); ?>>
<div id="loader" class="loader">
    <div class="plane-container">
        <div class="preloader-wrapper small active">
            <div class="spinner-layer spinner-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>

            <div class="spinner-layer spinner-red">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>

            <div class="spinner-layer spinner-yellow">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>

            <div class="spinner-layer spinner-green">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">

    <div class="app-header header-shadow">
        <div class="app-header__logo">
            <div class="logo">
                <?php
                if ( function_exists( 'the_custom_logo' ) ) {
                    the_custom_logo();
                }
                //echo get_bloginfo( 'name' );
                ?>
            </div>

        </div>
        <div class="app-header__mobile-menu">
            <div>
                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                </button>
            </div>
        </div>
        <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
        </div>    <div class="app-header__content">
            <?php /*
            <div class="app-header-left">
                <div class="search-wrapper">
                    <div class="input-holder">
                        <input type="text" class="search-input" placeholder="Tìm kiếm khách hàng, BTC, sản phẩm, sự kiện">
                        <button class="search-icon"><span></span></button>
                    </div>
                    <button class="close"></button>
                </div>
            </div>
 */ ?>
            <div class="app-header-right">

<!--                <a href="--><?php //echo SITE_URL; ?><!--/notification?view=listing" class="d-flex position-relative notification-public">-->
<!--                        <span class="icon-wrapper">-->
<!--                            <span class="icon-wrapper-bg bg-danger"></span>-->
<!--                            <i class="icon text-danger icon-anim-pulse ion-android-notifications"></i>-->
<!--                            <span class="badge badge-dot badge-dot-sm badge-danger"></span>-->
<!--                        </span>-->
<!--                    <div class="number-notification">-->
<!--                        <span>4</span>-->
<!--                    </div>-->
<!--                </a>-->

                <button type="button"  class="p-0 mr-2 btn  icon">
                    <a href="<?php echo SITE_URL; ?>/dashboard" class="d-flex position-relative notification-public">
                                <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                                    <span class="icon-wrapper-bg bg-primary"></span>
                                    <i class="icon text-primary ion-android-apps"></i>
                                </span>
                    </a>
                </button>

                <div class="header-btn-lg pr-0">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="btn-group dropdown">
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                        <i class="lnr-user icon-gradient bg-ripe-malin"></i>
                                        <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                    </a>
                                    <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-menu-header">
                                            <div class="dropdown-menu-header-inner bg-info">
                                                <div class="menu-header-image opacity-2"></div>
                                                <div class="menu-header-content text-left">
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-3">
                                                                <img width="42" class="rounded-circle"
                                                                     src="<?php echo $user_avatar;?>"
                                                                     alt="">
                                                            </div>
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading"><?php echo "Hi, ".$currentUser['name']; ?>
                                                                </div>
                                                                <div class="widget-subheading opacity-8"><?php echo $role_name; ?>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="scroll-area-xs" style="height: 80px;">
                                            <div class="scrollbar-container ps">
                                                <ul class="nav flex-column">
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="<?php echo $link_profile; ?>" class="nav-link">Thông tin tài khoản
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="<?php echo add_query_arg(['action' => 'logout'], site_url()); ?>" class="nav-link">Đăng xuất
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="widget-content-left  ml-3 header-user-info">
                                <div class="widget-heading">
                                    <?php echo "Hi, ".$currentUser['name']; ?>
                                </div>
                                <?php /*
                                <div class="widget-subheading">
	                                <?php echo $role_name; ?>
                                </div>
                                */ ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="app-main">
