<?php
if ( ! function_exists('pac_drh_menu_item_custom_fields')) {
    function pac_drh_menu_item_custom_fields($item_id, $item) {
        $_wp_nonce = wp_create_nonce(wp_basename(__FILE__));
        $_pac_drh_hide_on_desktop = get_post_meta($item_id, '_pac_drh_hide_on_desktop', true);
        $_pac_drh_hide_on_tablet = get_post_meta($item_id, '_pac_drh_hide_on_tablet', true);
        $_pac_drh_hide_on_mobile = get_post_meta($item_id, '_pac_drh_hide_on_mobile', true);
        ?>
        <input type="hidden" name="pac_drh_menu_item_nonce" value="<?php esc_attr_e($_wp_nonce); ?>">
        <div style="clear: both;"><?php esc_html_e('Show or hide the menu item on Desktop, Tablet, or Phone.', 'Divi'); ?></div>
        <div class="field-on-desktop description">
            <label for="pac-drh-enable-on-desktop-<?php esc_attr_e($item_id); ?>">
                <input type="checkbox" id="pac-drh-enable-on-desktop-<?php esc_attr_e($item_id); ?>" value="off" name="_pac_drh_hide_on_desktop[<?php esc_attr_e($item_id); ?>]" <?php
                checked($_pac_drh_hide_on_desktop, 'off', true); ?>><?php esc_html_e('Hide On Desktop', 'Divi'); ?> </label>
        </div>
        <div class="field-on-tablet description">
            <label for="pac-drh-enable-on-tablet-<?php esc_attr_e($item_id); ?>">
                <input type="checkbox" id="pac-drh-enable-on-tablet-<?php esc_attr_e($item_id); ?>" value="off" name="_pac_drh_hide_on_tablet[<?php esc_attr_e($item_id); ?>]" <?php
                checked($_pac_drh_hide_on_tablet, 'off', true); ?>><?php esc_html_e('Hide On Tablet', 'Divi'); ?> </label>
        </div>
        <div class="field-on-mobile description">
            <label for="pac-drh-enable-on-mobile-<?php esc_attr_e($item_id); ?>">
                <input type="checkbox" id="pac-drh-enable-on-mobile-<?php esc_attr_e($item_id); ?>" value="off" name="_pac_drh_hide_on_mobile[<?php esc_attr_e($item_id); ?>]" <?php checked($_pac_drh_hide_on_mobile,
                    'off', true); ?>><?php esc_html_e('Hide On Mobile', 'Divi'); ?> </label>
        </div>
        <?php
    }

    add_action('wp_nav_menu_item_custom_fields', 'pac_drh_menu_item_custom_fields', 10, 2);
}
// Save Menu Item
if ( ! function_exists('pac_drh_update_menu_item')) {
    function pac_drh_update_menu_item($menu_id, $menu_item_db_id) {
        if ( ! isset($_POST['pac_drh_menu_item_nonce']) || ! wp_verify_nonce(sanitize_text_field($_POST['pac_drh_menu_item_nonce']), wp_basename(__FILE__))) {
            return $menu_id;
        }
        $meta_keys = [
            '_pac_drh_hide_on_desktop',
            '_pac_drh_hide_on_tablet',
            '_pac_drh_hide_on_mobile',
        ];
        foreach ($meta_keys as $meta_key) {
            if (array_key_exists($meta_key, $_POST) && ! empty($_POST[$meta_key][$menu_item_db_id])) {
                update_post_meta($menu_item_db_id, $meta_key, sanitize_text_field($_POST[$meta_key][$menu_item_db_id]));
            } else {
                update_post_meta($menu_item_db_id, $meta_key, 'on');
            }
        }

        return $menu_id;
    }

    add_action('wp_update_nav_menu_item', 'pac_drh_update_menu_item', 10, 2);
}
// Add Classes
if ( ! function_exists('pac_drh_menu_css_class')) {
    function pac_drh_menu_css_class($classes, $item, $args) {
        if (isset($item->ID)) {
            $item_id = $item->ID;
            $_pac_drh_hide_on_desktop = get_post_meta($item_id, '_pac_drh_hide_on_desktop', true);
            $_pac_drh_hide_on_tablet = get_post_meta($item_id, '_pac_drh_hide_on_tablet', true);
            $_pac_drh_hide_on_mobile = get_post_meta($item_id, '_pac_drh_hide_on_mobile', true);
            if ( ! empty($_pac_drh_hide_on_desktop) && 'off' === $_pac_drh_hide_on_desktop) {
                $classes[] = 'pac_drh_hide_menu_item_desktop';
            }
            if ( ! empty($_pac_drh_hide_on_tablet) && 'off' === $_pac_drh_hide_on_tablet) {
                $classes[] = 'pac_drh_hide_menu_item_tablet';
            }
            if ( ! empty($_pac_drh_hide_on_mobile) && 'off' === $_pac_drh_hide_on_mobile) {
                $classes[] = 'pac_drh_hide_menu_item_mobile';
            }
        }

        return $classes;
    }

    add_filter('nav_menu_css_class', 'pac_drh_menu_css_class', 1, 3);
}
if ( ! function_exists('pac_drh_menu_css')) {
    function pac_drh_menu_css() {
        echo PHP_EOL;
        echo "<style>";
        echo "@media (min-width:981px){.pac_drh_hide_menu_item_desktop{display:none!important;}}";
        echo "@media (min-width:768px) and (max-width:980px){.pac_drh_hide_menu_item_tablet{display:none!important;}}";
        echo "@media (min-width:300px) and (max-width:767px){.pac_drh_hide_menu_item_mobile{display:none!important;}}";
        echo "</style>";
        echo PHP_EOL;
    }
}
add_action('wp_print_styles', 'pac_drh_menu_css');

