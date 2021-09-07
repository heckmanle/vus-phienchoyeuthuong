<?php
/**
 * The header for our theme
 *
 * @since 1.0
 * @version 1.0
 */
if ( ! defined( 'WPINC' ) ) { die; }
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php
    $template_directory_uri = get_template_directory_uri();
    ?>
    <link rel="shortcut icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon.png?v=1.2"/>
    <link rel="stylesheet" href="<?php echo THEME_URL; ?>/assets/cfonts/fonts.css?ver=2" media="all" />
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
    <?php wp_head(); ?>
    <?php
    wp_enqueue_style( 'select-tow-public-css', THEME_URL . '/assets/libs/select2/css/select2.css', array(), '', 'all' );
    //wp_enqueue_style( 'guest-style', THEME_URL . '/style.css', array(), '', 'all' );
    wp_enqueue_style( 'guest-style', THEME_URL . '/guest/styles.css', array(), '', 'all' );
    ?>
</head>
<body <?php body_class('light sidebar-mini sidebar-collapse'); ?>>
<div class="app-container app-theme-white ">
    <div class="mainsite-area">
<?php get_template_part( 'template-builder', 'page' ); ?>