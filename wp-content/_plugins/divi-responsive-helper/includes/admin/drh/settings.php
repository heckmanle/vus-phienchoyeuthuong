<?php
add_action('wp_footer', 'pac_drh_init_settings');
add_action('admin_footer', 'pac_drh_init_settings');
function pac_drh_init_settings() { ?>
    <style type="text/css">
        <?php if ( 'on' !== et_get_option('pac_drh_enable_presets') ){ ?>
        .pac_preset {
            display: none !important;
        }

        <?php } ?>
        <?php if ( 'on' !== et_get_option('pac_drh_enable_custom_preview') ){ ?>
        .pac_set_preview_size_input_filed {
            display: none !important;
        }

        <?php } ?>
    </style>
    <?php
}

?>
