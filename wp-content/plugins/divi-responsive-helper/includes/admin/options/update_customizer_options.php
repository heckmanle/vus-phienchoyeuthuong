<?php
//Layout: When Update Value From Options
add_action('update_option_et_divi', function ($previous_options, $current_options) {
    // Desktop Section Height
    if (isset($current_options['section_padding']) && isset($previous_options['section_padding']) && ($current_options['section_padding'] != $previous_options['section_padding'])) {
        et_update_option('pac_drh_desktop_section_height', absint($current_options['section_padding']));
    }
    if (isset($previous_options['pac_drh_desktop_section_height']) && isset($current_options['pac_drh_desktop_section_height'])) {
        if ($previous_options['pac_drh_desktop_section_height'] != $current_options['pac_drh_desktop_section_height']) {
            et_update_option('section_padding', absint($current_options['pac_drh_desktop_section_height']));
        }
    }
    // Desktop Row Height
    if (isset($current_options['row_padding']) && isset($previous_options['row_padding']) && ($current_options['row_padding'] != $previous_options['row_padding'])) {
        et_update_option('pac_drh_desktop_row_height', absint($current_options['row_padding']));
    }
    if (isset($previous_options['pac_drh_desktop_row_height']) && isset($current_options['pac_drh_desktop_row_height'])) {
        if ($previous_options['pac_drh_desktop_row_height'] != $current_options['pac_drh_desktop_row_height']) {
            et_update_option('row_padding', absint($current_options['pac_drh_desktop_row_height']));
        }
    }
    // Tablet Section Height
    if (isset($current_options['tablet_section_height']) && isset($previous_options['tablet_section_height']) && ($current_options['tablet_section_height'] != $previous_options['tablet_section_height'])) {
        et_update_option('pac_drh_tablet_section_height', absint($current_options['tablet_section_height']));
    }
    if (isset($previous_options['pac_drh_tablet_section_height']) && isset($current_options['pac_drh_tablet_section_height'])) {
        if ($previous_options['pac_drh_tablet_section_height'] != $current_options['pac_drh_tablet_section_height']) {
            et_update_option('tablet_section_height', absint($current_options['pac_drh_tablet_section_height']));
        }
    }
    // Tablet Row Height
    if (isset($current_options['tablet_row_height']) && isset($previous_options['tablet_row_height']) && ($current_options['tablet_row_height'] != $previous_options['tablet_row_height'])) {
        et_update_option('pac_drh_tablet_row_height', absint($current_options['tablet_row_height']));
    }
    if (isset($previous_options['pac_drh_tablet_row_height']) && isset($current_options['pac_drh_tablet_row_height'])) {
        if ($previous_options['pac_drh_tablet_row_height'] != $current_options['pac_drh_tablet_row_height']) {
            et_update_option('tablet_row_height', absint($current_options['pac_drh_tablet_row_height']));
        }
    }
    // Phone Section Height
    if (isset($current_options['phone_section_height']) && isset($previous_options['phone_section_height']) && ($current_options['phone_section_height'] != $previous_options['phone_section_height'])) {
        et_update_option('pac_drh_phone_section_height', absint($current_options['phone_section_height']));
    }
    if (isset($previous_options['pac_drh_phone_section_height']) && isset($current_options['pac_drh_phone_section_height'])) {
        if ($previous_options['pac_drh_phone_section_height'] != $current_options['pac_drh_phone_section_height']) {
            et_update_option('phone_section_height', absint($current_options['pac_drh_phone_section_height']));
        }
    }
    // Phone Row Height
    if (isset($current_options['phone_row_height']) && isset($previous_options['phone_row_height']) && ($current_options['phone_row_height'] != $previous_options['phone_row_height'])) {
        et_update_option('pac_drh_phone_row_height', absint($current_options['phone_row_height']));
    }
    if (isset($previous_options['pac_drh_phone_row_height']) && isset($current_options['pac_drh_phone_row_height'])) {
        if ($previous_options['pac_drh_phone_row_height'] != $current_options['pac_drh_phone_row_height']) {
            et_update_option('phone_row_height', absint($current_options['pac_drh_phone_row_height']));
        }
    }
}, 10, 2);