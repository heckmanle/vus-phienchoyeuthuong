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
if( empty($currentUser) || is_wp_error($currentUser) ){
    wp_redirect(add_query_arg(['view' => 'login-email'], get_the_permalink(get_page_by_path(__('Authentication', AUTHENTICATION_LANG_DOMAIN)))));
    exit;
}
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
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon.png?v=1.2"/>

    <link rel="stylesheet" href="<?php echo THEME_URL; ?>/assets/cfonts/fonts.css?ver=2" media="all" />

    <script>
        const IMAGE_LOADING = {
            message: '<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            css: {border: '', padding: 'none', width: '40px', height: '40px'}

        };
        const AJAX_URL = "<?php echo admin_url('admin-ajax.php'); ?>",
            THOUNSAND_SEP = "<?php echo THOUNSAND_SEP; ?>",
            NUM_DECIMAL = "<?php echo NUM_DECIMAL; ?>",
            DECIMAL_SEP = "<?php echo DECIMAL_SEP; ?>";

        const MESSAGES_VALIDATORS = {
            required: "<?= __("Tr?????ng n??y kh??ng ???????c ????? tr???ng."); ?>",
            remote: "<?= __("Vui l??ng s???a tr?????ng n??y."); ?>",
            email: "<?= __("Vui l??ng nh???p email ????ng ?????nh d???ng."); ?>",
            url: "<?= __("Vui l??ng nh???n ???????ng d???n ????ng ?????nh d???ng."); ?>",
            date: "<?= __("Vui l??ng nh???p ng??y th??ng ????ng ?????nh d???ng."); ?>",
            dateISO: "<?= __("Vui l??ng nh???p ng??y th??ng ????ng ?????nh d???ng (ISO)."); ?>",
            number: "<?= __("Vui l??ng nh???p m???t s??? h???p l???."); ?>",
            digits: "<?= __("Vui l??ng ch??? nh???p c??c ch??? s???."); ?>",
            equalTo: "<?= __("Vui l??ng nh???p l???i c??ng m???t gi?? tr???."); ?>",
            maxlength: "<?= __("Vui l??ng nh???p kh??ng qu?? {0} k?? t???."); ?>",
            minlength: "<?= __("Vui l??ng nh???p ??t nh???t {0} k?? t???."); ?>",
            rangelength: "<?= __("Vui l??ng nh???p m???t gi?? tr??? trong kho???ng t??? {0} ?????n {1} k?? t???."); ?>",
            range: "<?= __("Vui l??ng nh???p gi?? tr??? trong kho???ng t??? {0} ?????n {1}."); ?>",
            max: "<?= __("Vui l??ng nh???p m???t gi?? tr??? nh??? h??n ho???c b???ng {0}."); ?>",
            min: "<?= __("Vui l??ng nh???p m???t gi?? tr??? l???n h??n ho???c b???ng {0}."); ?>",
            step: "<?= __("Vui l??ng nh???p m???t b???i s??? c???a {0}."); ?>"
        }
    </script>
    <?php wp_head(); ?>
    <?php
    wp_enqueue_style( 'select-tow-public-css', THEME_URL . '/assets/libs/select2/css/select2.css', array(), '', 'all' );
    ?>
</head>
<body <?php body_class('light sidebar-mini sidebar-collapse'); ?>>
<div>
    <div>