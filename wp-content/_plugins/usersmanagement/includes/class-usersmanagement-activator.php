<?php

/**
 * Fired during plugin activation
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    usersmanagement
 * @subpackage usersmanagement/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    usersmanagement
 * @subpackage usersmanagement/includes
 * @author     #
 */
class Usersmanagement_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        self::add_pages();
	}
    static function add_pages()
    {
        $attr = [
            'post_title' => __('Quản lý người dùng', ''),
            'post_name' => USERSMANAGEMENT,
            'post_type' => 'page',
            'post_status' => 'publish',
            'page_template' => "page-usersmanagement.php"
        ];

        $page = get_page_by_path($attr['post_name'], OBJECT, 'page');

        if ($page === null) {
            $attr['ID'] = wp_insert_post($attr);

            if (!empty($attr['ID']) && !is_wp_error($attr['ID'])) {
                update_post_meta($attr['ID'], '_wp_page_template', $attr['page_template']);
            }

        } else if ($page instanceof \WP_Post) {
            $attr['ID'] = $page->ID;
            wp_update_post($attr);
            update_post_meta($attr['ID'], '_wp_page_template', $attr['page_template']);
        }
    }

}
