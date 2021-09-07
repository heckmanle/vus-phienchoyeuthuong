<?php
/*Add Theme Options*/
add_action("epanel_render_maintabs", 'pac_drh_epanel_tabs');
function pac_drh_epanel_tabs() {
    pac_drh_epanel_fields();
    echo sprintf('<li><a href="#wrap-drh">%s</a></li>', esc_html__('Divi Responsive Helper', 'Divi'));
}

/*Add Theme Tabs Options*/
add_action("et_epanel_changing_options", 'pac_drh_epanel_fields');
if ( ! function_exists('pac_drh_epanel_fields')) {
    function pac_drh_epanel_fields() {
        global $options, $shortname;
        $options[] = [
            "name" => "wrap-drh",
            "type" => "contenttab-wrapstart",
        ];
        $options[] = [
            "type" => "subnavtab-start",
        ];
        $options[] = [
            "name" => "drh-1",
            "type" => "subnav-tab",
            "desc" => esc_html__("Preview Size", $shortname)
        ];
        $options[] = [
            "name" => "drh-2",
            "type" => "subnav-tab",
            "desc" => esc_html__("Widow Fixer", $shortname)
        ];
        $options[] = [
            "name" => "drh-3",
            "type" => "subnav-tab",
            "desc" => esc_html__("Column Stacking", $shortname)
        ];
        $options[] = [
            "name" => "drh-4",
            "type" => "subnav-tab",
            "desc" => esc_html__("Text Sizes", $shortname)
        ];
        $options[] = [
            "name" => "drh-5",
            "type" => "subnav-tab",
            "desc" => esc_html__("Menu", $shortname)
        ];
        $options[] = [
            "name" => "drh-6",
            "type" => "subnav-tab",
            "desc" => esc_html__("CSS Media Queries", $shortname)
        ];
        $options[] = [
            "name" => "drh-7",
            "type" => "subnav-tab",
            "desc" => esc_html__("Layout", $shortname)
        ];
        $options[] = [
            "name" => "drh-8",
            "type" => "subnav-tab",
            "desc" => esc_html__("Miscellaneous", $shortname)
        ];
        $options[] = [
            "type" => "subnavtab-end",
        ];
        $options[] = [
            "name" => "drh-1",
            "type" => "subcontent-start",
        ];
        $options[] = [
            "name" => esc_html__('Preview Size Presets', $shortname),
            "id" => "pac_drh_enable_presets",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to show the preview size preset buttons in the Visual Builder page settings (located in the bottom left corner by default).", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Phone Preset Button One', $shortname),
            "id" => "pac_drh_phone_preset_one",
            "type" => "text",
            "std" => "320",
            "desc" => esc_html__("Enter a custom value for the 1st preview size preset button in the Phone view.", $shortname),
            "validation_type" => "number"
        ];
        $options[] = [
            "name" => esc_html__('Phone Preset Button Two', $shortname),
            "id" => "pac_drh_phone_preset_two",
            "type" => "text",
            "std" => "479",
            "desc" => esc_html__("Enter a custom value for the 2nd preview size preset button in the Phone view.", $shortname),
            "validation_type" => "number"
        ];
        $options[] = [
            "name" => esc_html__('Phone Preset Button Three', $shortname),
            "id" => "pac_drh_phone_preset_three",
            "type" => "text",
            "std" => "767",
            "desc" => esc_html__("Enter a custom value for the 3rd preview size preset button in the Phone view.", $shortname),
            "validation_type" => "number"
        ];
        $options[] = [
            "name" => esc_html__('Tablet Preset Button One', $shortname),
            "id" => "pac_drh_tablet_preset_one",
            "type" => "text",
            "std" => "768",
            "desc" => esc_html__("Enter a custom value for the 1st preview size preset button in the Tablet view.", $shortname),
            "validation_type" => "number"
        ];
        $options[] = [
            "name" => esc_html__('Tablet Preset Button Two', $shortname),
            "id" => "pac_drh_tablet_preset_two",
            "type" => "text",
            "std" => "880",
            "desc" => esc_html__("Enter a custom value for the 2nd preview size preset button in the Tablet view.", $shortname),
            "validation_type" => "number"
        ];
        $options[] = [
            "name" => esc_html__('Tablet Preset Button Three', $shortname),
            "id" => "pac_drh_tablet_preset_three",
            "type" => "text",
            "std" => "980",
            "desc" => esc_html__("Enter a custom value for the 3rd preview size preset button in the Tablet view.", $shortname),
            "validation_type" => "number"
        ];
        $options[] = [
            "name" => esc_html__('Desktop Preset Button One', $shortname),
            "id" => "pac_drh_desktop_preset_one",
            "type" => "text",
            "std" => "982",
            "desc" => esc_html__("Enter a custom value for the 1st preview size preset button in the Desktop view.", $shortname),
            "validation_type" => "number"
        ];
        $options[] = [
            "name" => esc_html__('Desktop Preset Button Two', $shortname),
            "id" => "pac_drh_desktop_preset_two",
            "type" => "text",
            "std" => "1024",
            "desc" => esc_html__("Enter a custom value for the 2nd preview size preset button in the Desktop view.", $shortname),
            "validation_type" => "number"
        ];
        $options[] = [
            "name" => esc_html__('Desktop Preset Button Three', $shortname),
            "id" => "pac_drh_desktop_preset_three",
            "type" => "text",
            "std" => "1200",
            "desc" => esc_html__("Enter a custom value for the 3rd preview size preset button in the Desktop view.", $shortname),
            "validation_type" => "number"
        ];
        $options[] = [
            "name" => esc_html__('Preview Size Custom Value', $shortname),
            "id" => "pac_drh_enable_custom_preview",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => 'Enable this feature to show the preview size custom value field in the Visual Builder page settings (located in the bottom left corner by default).',
        ];
        $options[] = [
            "name" => esc_html__('Disable Divi Responsive Views Settings', $shortname),
            "id" => "pac_drh_enable_hide_responsive_view",
            "type" => "checkbox2",
            "std" => "on",
            "desc" => esc_html__("Enable this feature to completely remove the default Divi Responsive Views settings.", $shortname),
        ];
        $options[] = [
            "name" => "drh-1",
            "type" => "subcontent-end",
        ];
        $options[] = [
            "name" => "drh-2",
            "type" => "subcontent-start",
        ];
        $options[] = [
            "name" => esc_html__('Widow Fixer', $shortname),
            "id" => "pac_drh_enable_widow_fixer",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable the Widow Fixer feature.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Paragraphs', $shortname),
            "id" => "pac_drh_enable_paragraph_widow_fixer",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to remove the widow words on the last line of paragraph text.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Minimum Paragraph Widow Words', $shortname),
            "id" => "pac_drh_widow_fixer_paragraph_select",
            "type" => "select",
            "std" => "asc",
            "options" => [2, 3, 4],
            "desc" => esc_html__(" Select the minimum number of words allowed on the last line of each paragraph.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Headings', $shortname),
            "id" => "pac_drh_widow_fixer_headings",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable the Widow Fixer feature for headings (H1-H6).", $shortname),
            "class" => 'my_class',
        ];
        $options[] = [
            "name" => esc_html__('Minimum Heading Widow Words', $shortname),
            "id" => "pac_drh_widow_fixer_heading_select",
            "type" => "select",
            "std" => "asc",
            "options" => [2, 3, 4],
            "desc" => esc_html__(" Select the minimum number of words allowed on the last line of each heading.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Exclude Pages', $shortname),
            "id" => "pac_drh_enable_pages_widow_fixer",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to excluded the Widow Fixer on selected pages.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Select Pages To Exclude', $shortname),
            "id" => "pac_drh_pages_widow_fixer",
            "type" => "checkboxes",
            "desc" => esc_html__("Choose which pages you want to include or exclude from the Widow Fixer settings. The Widow Fixer will apply to all pages marked with a checkmark, and all pages marked with an X will be excluded.",
                $shortname),
            "usefor" => "pages",
            "options" => pac_drh_get_pages_list(),
        ];
        $options[] = [
            "name" => "drh-2",
            "type" => "subcontent-end",
        ];
        $options[] = [
            "name" => "drh-3",
            "type" => "subcontent-start",
        ];
        $options[] = [
            "name" => esc_html__('Row Column Stacking Order', $shortname),
            "id" => "pac_drh_enable_col_stacking",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to activate the additional settings located in the Advanced tab of the Divi Builder Row and Column settings for choosing the order in which columns stack on each device.",
                $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Row Number of Columns', $shortname),
            "id" => "pac_drh_enable_number_of_columns",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to activate the additional settings located in the Advanced tab of the Divi Builder Row settings for choosing the number of columns to show on each device. ",
                $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Blog Module Number of Columns', $shortname),
            "id" => "pac_drh_enable_blog_number_of_columns",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to activate the additional settings located in the Design tab of the Divi Blog module settings when the Grid Layout is used for choosing the number of blog columns to show on each device.",
                $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Portfolio Modules Number of Columns', $shortname),
            "id" => "pac_drh_enable_portfolio_number_of_columns",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to activate the additional settings located in the Design tab of both the Divi Portfolio module and Divi Filterable Portfolio module settings when the Grid Layout is used for choosing the number of portfolio columns to show on each device.",
                $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Gallery Module Number of Columns', $shortname),
            "id" => "pac_drh_enable_gallery_number_of_columns",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to activate the additional settings located in the Design tab of the Divi Gallery module settings when the Grid Layout is used for choosing the number of image columns to show on each device.",
                $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Shop Module Number of Columns', $shortname),
            "id" => "pac_drh_enable_shop_number_of_columns",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to activate the additional settings located in the Content tab of the Divi Shop module settings for choosing the number of product columns to show on each device.",
                $shortname),
        ];
        $options[] = [
            "name" => "drh-3",
            "type" => "subcontent-end",
        ];
        $options[] = [
            "name" => "drh-4",
            "type" => "subcontent-start",
        ];
        $options[] = [
            "name" => esc_html__('Text Sizes', $shortname),
            "id" => "pac_drh_enable_text_sizes",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to set the heading (H1, H2, H3, H4, H5, H6) and paragraph text sizes to apply across the entire website on Desktop, Tablet, and Phone.",
                $shortname),
        ];
        $pac_drh_desktop_heading = absint(et_get_option('body_header_size', '42'));
        $pac_drh_tablet_heading = absint(et_get_option('tablet_header_font_size', '38'));
        $pac_drh_phone_heading = absint(et_get_option('phone_header_font_size', '34'));
        $options[] = [
            "name" => esc_html__('Heading H1 For Desktop', $shortname),
            "id" => "pac_drh_h1_desktop",
            "type" => "text",
            "std" => $pac_drh_desktop_heading.'px',
            "desc" => esc_html__("Set the H1 heading text size value and unit to apply across the entire website on Desktop.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H1 For Tablet', $shortname),
            "id" => "pac_drh_h1_tablet",
            "type" => "text",
            "std" => $pac_drh_tablet_heading.'px',
            "desc" => esc_html__("Set the H1 heading text size value and unit to apply across the entire website on Tablet.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H1 For Phone', $shortname),
            "id" => "pac_drh_h1_phone",
            "type" => "text",
            "std" => $pac_drh_phone_heading.'px',
            "desc" => esc_html__("Set the H1 heading text size value and unit to apply across the entire website on Phone.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H2 For Desktop', $shortname),
            "id" => "pac_drh_h2_desktop",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_desktop_heading * .86)).'px',
            "desc" => esc_html__("Set the H2 heading text size value and unit to apply across the entire website on Desktop.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H2 For Tablet', $shortname),
            "id" => "pac_drh_h2_tablet",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_tablet_heading * .86)).'px',
            "desc" => esc_html__("Set the H2 heading text size value and unit to apply across the entire website on Tablet.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H2 For Phone', $shortname),
            "id" => "pac_drh_h2_phone",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_phone_heading * .86)).'px',
            "desc" => esc_html__("Set the H2 heading text size value and unit to apply across the entire website on Phone.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H3 For Desktop', $shortname),
            "id" => "pac_drh_h3_desktop",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_desktop_heading * .73)).'px',
            "desc" => esc_html__("Set the H3 heading text size value and unit to apply across the entire website on Desktop.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H3 For Tablet', $shortname),
            "id" => "pac_drh_h3_tablet",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_tablet_heading * .73)).'px',
            "desc" => esc_html__("Set the H3 heading text size value and unit to apply across the entire website on Tablet.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H3 For Phone', $shortname),
            "id" => "pac_drh_h3_phone",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_phone_heading * .73)).'px',
            "desc" => esc_html__("Set the H3 heading text size value and unit to apply across the entire website on Phone.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H4 For Desktop', $shortname),
            "id" => "pac_drh_h4_desktop",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_desktop_heading * .6)).'px',
            "desc" => esc_html__("Set the H4 heading text size value and unit to apply across the entire website on Desktop.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H4 For Tablet', $shortname),
            "id" => "pac_drh_h4_tablet",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_tablet_heading * .6)).'px',
            "desc" => esc_html__("Set the H4 heading text size value and unit to apply across the entire website on Tablet.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H4 For Phone', $shortname),
            "id" => "pac_drh_h4_phone",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_phone_heading * .6)).'px',
            "desc" => esc_html__("Set the H4 heading text size value and unit to apply across the entire website on Phone.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H5 For Desktop', $shortname),
            "id" => "pac_drh_h5_desktop",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_desktop_heading * .53)).'px',
            "desc" => esc_html__("Set the H5 heading text size value and unit to apply across the entire website on Desktop.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H5 For Tablet', $shortname),
            "id" => "pac_drh_h5_tablet",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_tablet_heading * .53)).'px',
            "desc" => esc_html__("Set the H5 heading text size value and unit to apply across the entire website on Tablet.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H5 For Phone', $shortname),
            "id" => "pac_drh_h5_phone",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_phone_heading * .53)).'px',
            "desc" => esc_html__("Set the H5 heading text size value and unit to apply across the entire website on Phone.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H6 For Desktop', $shortname),
            "id" => "pac_drh_h6_desktop",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_desktop_heading * .47)).'px',
            "desc" => esc_html__("Set the H6 heading text size value and unit to apply across the entire website on Desktop.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H6 For Tablet', $shortname),
            "id" => "pac_drh_h6_tablet",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_tablet_heading * .47)).'px',
            "desc" => esc_html__("Set the H6 heading text size value and unit to apply across the entire website on Tablet.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Heading H6 For Phone', $shortname),
            "id" => "pac_drh_h6_phone",
            "type" => "text",
            "std" => esc_html(intval($pac_drh_phone_heading * .47)).'px',
            "desc" => esc_html__("Set the H6 heading text size value and unit to apply across the entire website on Phone.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Paragraph For Desktop', $shortname),
            "id" => "pac_drh_p_desktop",
            "type" => "text",
            "std" => absint(et_get_option('body_font_size', '20')).'px',
            "desc" => esc_html__("Set the paragraph text size value and unit to apply across the entire website on Desktop.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Paragraph For Tablet', $shortname),
            "id" => "pac_drh_p_tablet",
            "type" => "text",
            "std" => absint(et_get_option('tablet_body_font_size', '18')).'px',
            "desc" => esc_html__("Set the paragraph text size value and unit to apply across the entire website on Tablet.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Paragraph For Phone', $shortname),
            "id" => "pac_drh_p_phone",
            "type" => "text",
            "std" => absint(et_get_option('phone_body_font_size', '16')).'px',
            "desc" => esc_html__("Set the paragraph text size value and unit to apply across the entire website on Phone.", $shortname),
        ];
        $options[] = [
            "name" => "drh-4",
            "type" => "subcontent-end",
        ];
        $options[] = [
            "name" => "drh-5",
            "type" => "subcontent-start",
        ];
        $options[] = [
            "name" => esc_html__('Choose Navigation Menu Per Device In Menu Module', $shortname),
            "id" => "pac_drh_enable_responsive_menu",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this setting to activate additional responsive settings in the Menu module for choosing a different navigation menu for Desktop, Tablet, and Phone.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Mobile Menu Opened Icon', $shortname),
            "id" => "pac_drh_enable_open_mobile_icon",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Change the mobile menu hamburger icon to an X when the dropdown menu it is toggled open.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Show/Hide Menu Items', $shortname),
            "id" => "pac_drh_enable_show_hide_menu_item",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to add new checkmarks in the WordPress navigation menu items to show or hide the menu items on Desktop, Tablet, or Phone.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Mobile Menu Breakpoint', $shortname),
            "id" => "pac_drh_menu_to_mobile_menu",
            "type" => "text",
            "std" => "",
            "desc" => esc_html__("Enter the screen width pixel value for the responsive breakpoint for for when the menu changes between desktop and mobile versions.", $shortname),
        ];
        $options[] = [
            "name" => "drh-5",
            "type" => "subcontent-end",
        ];
        $options[] = [
            "name" => "drh-6",
            "type" => "subcontent-start",
        ];
        $options[] = [
            "name" => esc_html__("Custom CSS For Desktop Only", $shortname),
            "id" => "pac_drh_desktop_media_query",
            "type" => "textarea",
            "std" => "",
            "desc" => esc_html__("Place any custom CSS here that you want to apply on Desktop device sizes only (981px and up).", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__("Custom CSS For Tablet Only", $shortname),
            "id" => "pac_drh_tablet_media_query",
            "type" => "textarea",
            "std" => "",
            "desc" => esc_html__("Place any custom CSS here that you want to apply on Tablet device sizes only (767px - 980px).", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__("Custom CSS For Phone Only", $shortname),
            "id" => "pac_drh_phone_media_query",
            "type" => "textarea",
            "std" => "",
            "desc" => esc_html__("Place any custom CSS here that you want to apply on Phone device sizes only (0px - 767px).", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__("Custom CSS For Desktop And Tablet Only", $shortname),
            "id" => "pac_drh_desktop_tablet_media_query",
            "type" => "textarea",
            "std" => "",
            "desc" => esc_html__("Place any custom CSS here that you want to apply on Desktop and Tablet device sizes only (0px - 767px).", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__("Custom CSS For Tablet and Phone Only", $shortname),
            "id" => "pac_drh_tablet_phone_media_query",
            "type" => "textarea",
            "std" => "",
            "desc" => esc_html__("Place any custom CSS here that you want to apply on Tablet and Phone device sizes only (0px - 767px).", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => "drh-6",
            "type" => "subcontent-end",
        ];
        $pac_drh_desktop_section_height = esc_html(et_get_option('section_padding'));
        $pac_drh_tablet_section_height = esc_html(et_get_option('tablet_section_height'));
        $pac_drh_phone_section_height = esc_html(et_get_option('phone_section_height'));
        $pac_drh_desktop_row_height = esc_html(et_get_option('row_padding'));
        $pac_drh_tablet_row_height = esc_html(et_get_option('tablet_row_height'));
        $pac_drh_phone_row_height = esc_html(et_get_option('phone_row_height'));
        $options[] = [
            "name" => "drh-7",
            "type" => "subcontent-start",
        ];
        $options[] = [
            "name" => esc_html__('Section Default Top/Bottom Padding On Desktop (%)', $shortname),
            "id" => "pac_drh_desktop_section_height",
            "type" => "text",
            "std" => $pac_drh_desktop_section_height,
            "desc" => esc_html__("Set the default top and bottom padding percentage value to apply to all sections across the entire website on Desktop.", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__('Section Default Top/Bottom Padding On Tablet (px)', $shortname),
            "id" => "pac_drh_tablet_section_height",
            "type" => "text",
            "std" => $pac_drh_tablet_section_height,
            "desc" => esc_html__("Set the default top and bottom padding pixel value to apply to all sections across the entire website on Tablet.", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__('Section Default Top/Bottom Padding On Phone (px)', $shortname),
            "id" => "pac_drh_phone_section_height",
            "type" => "text",
            "std" => $pac_drh_phone_section_height,
            "desc" => esc_html__("Set the default top and bottom padding pixel value to apply to all sections across the entire website on Phone.", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__('Row Default Top/Bottom Padding On Desktop (%)', $shortname),
            "id" => "pac_drh_desktop_row_height",
            "type" => "text",
            "std" => $pac_drh_desktop_row_height,
            "desc" => esc_html__("Set the default top and bottom padding percentage value to apply to all rows across the entire website on Desktop.", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__('Row Default Top/Bottom Padding On Tablet (px)', $shortname),
            "id" => "pac_drh_tablet_row_height",
            "type" => "text",
            "std" => $pac_drh_tablet_row_height,
            "desc" => esc_html__("Set the default top and bottom padding pixel value to apply to all rows across the entire website on Tablet.", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__('Row Default Top/Bottom Padding On Phone (px)', $shortname),
            "id" => "pac_drh_phone_row_height",
            "type" => "text",
            "std" => $pac_drh_phone_row_height,
            "desc" => esc_html__("Set the default top and bottom padding pixel value to apply to all rows across the entire website on Phone.", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__('Row Default Width On Desktop (%)', $shortname),
            "id" => "pac_drh_row_width_desktop",
            "type" => "text",
            "std" => "",
            "desc" => esc_html__("Set the default width percentage value to apply to all rows across the entire website on Desktop.", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__('Row Default Width On Tablet (%)', $shortname),
            "id" => "pac_drh_row_width_tablet",
            "type" => "text",
            "std" => "",
            "desc" => esc_html__("Set the default width percentage value to apply to all rows across the entire website on Tablet.", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__('Row Default Width On Phone (%)', $shortname),
            "id" => "pac_drh_row_width_phone",
            "type" => "text",
            "std" => "",
            "desc" => esc_html__("Set the default width percentage value to apply to all rows across the entire website on Phone.", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__('Row Default Max Width On Desktop (px)', $shortname),
            "id" => "pac_drh_row_max_width_desktop",
            "type" => "text",
            "std" => "",
            "desc" => esc_html__("Set the default max-width pixel value to all rows across the entire website on Desktop.", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__('Row Default Max Width On Tablet (px)', $shortname),
            "id" => "pac_drh_row_max_width_tablet",
            "type" => "text",
            "std" => "",
            "desc" => esc_html__("Set the default max-width pixel value to apply to all rows across the entire website on Phone.", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => esc_html__('Row Default Max Width On Phone (px)', $shortname),
            "id" => "pac_drh_row_max_width_phone",
            "type" => "text",
            "std" => "",
            "desc" => esc_html__("Set the default max-width value and unit to apply to all rows across the entire website on Phone.", $shortname),
            "validation_type" => "nohtml"
        ];
        $options[] = [
            "name" => "drh-7",
            "type" => "subcontent-end",
        ];
        $options[] = [
            "name" => "drh-8",
            "type" => "subcontent-start",
        ];
        $options[] = [
            "name" => esc_html__('Auto Open Responsive Tabs', $shortname),
            "id" => "pac_drh_enable_auto_responsive",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable and this feature to automatically open the responsive Desktop, Tablet and Phone tabs in the Visual Builder.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Parallax On Mobile Devices', $shortname),
            "id" => "pac_drh_enable_mobile_parallax",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to automatically add support for parallax effect on mobile devices whenever the Use Parallax Effect setting is enabled within any Divi section, row, column, or module.",
                $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Disable Animations On Mobile', $shortname),
            "id" => "pac_drh_enable_remove_animation",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to disable animations on mobile to improve performance.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Prevent Horizontal Scroll', $shortname),
            "id" => "pac_drh_enable_prevent_horizontal_scroll",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to prevent a common issue with Divi which causes the mobile site to scroll sideways with a horizontal scroll bar.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Mobile Pinch Zooming', $shortname),
            "id" => "pac_drh_enable_mobile_pinch_zoom",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to allow users to pinch and zoom on mobile.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__("Mobile Logo", $shortname),
            "id" => "pac_drh_mobile_logo",
            "type" => "upload",
            "button_text" => esc_html__("Set As Logo", $shortname),
            "std" => "",
            "desc" => esc_html__("Set a different logo image to appear in the default header menu on tablet and phone devices.", $shortname),
            "validation_type" => "url",
        ];
        $options[] = [
            "name" => esc_html__("Mobile Header Bar Color", $shortname),
            "id" => "pac_drh_mobile_header_color",
            "type" => "et_color_palette",
            "items_amount" => 1,
            "std" => '#FFFFFF',
            "desc" => esc_html__("Set a color for the mobile address bar header for Android devices using the Chrome browser.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Tabs Module Tab Layout', $shortname),
            "id" => "pac_drh_enable_tabs_layout_settings",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to show settings in the Design tab of the Divi Tabs module for setting the tab stacking layout.", $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Blurb Image/Icon Position On Phone', $shortname),
            "id" => "pac_drh_enable_blurb_settings",
            "type" => "checkbox2",
            "std" => "off",
            "desc" => esc_html__("Enable this feature to activate new settings in the Design tab of the Divi Blurb module settings for setting the blurb image/icon position on Phone devices when the default placement is set to left.",
                $shortname),
        ];
        $options[] = [
            "name" => esc_html__('Back To Top Button Visibility', $shortname),
            "id" => "pac_drh_back_top_visibility",
            "type" => "select",
            "options" => [
                "off" => "Show On All Devices",
                "desktop" => "Show On Desktop",
                "tablet" => "Show On Tablet",
                "phone" => "Show On Phone",
                "desktop_tablet" => "Show On Desktop And Tablet",
                "tablet_phone" => "Show On Tablet And Phone",
                "desktop_phone" => "Show On Desktop And Phone",
            ],
            "std" => "off",
            'usefor' => 'custom',
            'et_save_values' => true,
            "desc" => esc_html__("Choose on which devices to show the Back To Top Button. This feature first requires the main Back To Top Button to be enabled in Divi>Theme Options>General.",
                $shortname),
        ];
        $options[] = [
            "name" => "drh-8",
            "type" => "subcontent-end",
        ];
        $options[] = [
            "name" => "wrap-drh",
            "type" => "contenttab-wrapend",
        ];
    }
}
if ( ! function_exists('pac_drh_get_pages_list')) {
    function pac_drh_get_pages_list() {
        $pages_ids = [];
        $pages = get_pages('hide_empty=0');
        if ( ! empty($pages)) {
            foreach ($pages as $page) {
                $site_pages[$page->ID] = htmlspecialchars($page->post_title);
                $pages_ids[] = $page->ID;
            }
        }
        $pages_ids = array_map('intval', $pages_ids);

        return $pages_ids;
    }
}

