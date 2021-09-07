window.parent_of_auto_close_responsive = '';
window.parent_of_enabel_text_sizes = '';
window.parent_of_enable_widow_fixer = '';
window.parent_of_paragraph_widow_fixer = '';
window.parent_of_paragraph_select_widow_select = '';
window.parent_of_heading_select_widow_select = '';
window.parent_of_widwo_fixer_headings = '';
window.yes_no_button_of_enable_widow_fixer = '';
window.yes_no_button_of_paragraph_widow_fixer = '';
window.yes_no_button_to_enable_custom_presets = '';
window.pac_drh_phone_preset_one = '';
window.pac_drh_phone_preset_two = '';
window.pac_drh_phone_preset_three = '';
window.pac_drh_tablet_preset_one = '';
window.pac_drh_tablet_preset_two = '';
window.pac_drh_tablet_preset_three = '';
window.pac_drh_desktop_preset_one = '';
window.pac_drh_desktop_preset_two = '';
window.pac_drh_desktop_preset_three = '';
// Pages
window.parent_of_pages_widow_fixer = '';
window.parent_of_pages_list_widow_fixer = '';
window.yes_no_button_of_pages_widow_fixer = '';
jQuery(document).ready(function() {
    if (jQuery('#pac_drh_widow_fixer_headings').length) {
        parent_of_enable_widow_fixer = jQuery('#pac_drh_enable_widow_fixer').parents().eq(1);
        parent_of_paragraph_widow_fixer = jQuery('#pac_drh_enable_paragraph_widow_fixer').parents().eq(1);
        parent_of_paragraph_select_widow_select = jQuery('#pac_drh_widow_fixer_paragraph_select').parents().eq(1);
        parent_of_heading_select_widow_select = jQuery('#pac_drh_widow_fixer_heading_select').parents().eq(1);
        parent_of_widwo_fixer_headings = jQuery('#pac_drh_widow_fixer_headings').parents().eq(1);
        //Pages
        parent_of_pages_widow_fixer = jQuery('#pac_drh_enable_pages_widow_fixer').parents().eq(1);
        parent_of_pages_list_widow_fixer = jQuery('#pac_drh_enable_pages_widow_fixer').parent().parent().next('.et-epanel-box');
    }
    parent_of_auto_close_responsive = jQuery('#pac_drh_enable_auto_close_responsive').parents().eq(1);
    parent_of_yes_no_button_to_enable_custom_presets = jQuery('#pac_drh_enable_presets').parents().eq(1);
    parent_of_pac_drh_phone_preset_one = jQuery('#pac_drh_phone_preset_one').parents().eq(1);
    parent_of_pac_drh_phone_preset_two = jQuery('#pac_drh_phone_preset_two').parents().eq(1);
    parent_of_pac_drh_phone_preset_three = jQuery('#pac_drh_phone_preset_three').parents().eq(1);
    parent_of_pac_drh_tablet_preset_one = jQuery('#pac_drh_tablet_preset_one').parents().eq(1);
    parent_of_pac_drh_tablet_preset_two = jQuery('#pac_drh_tablet_preset_two').parents().eq(1);
    parent_of_pac_drh_tablet_preset_three = jQuery('#pac_drh_tablet_preset_three').parents().eq(1);
    parent_of_pac_drh_desktop_preset_one = jQuery('#pac_drh_desktop_preset_one').parents().eq(1);
    parent_of_pac_drh_desktop_preset_two = jQuery('#pac_drh_desktop_preset_two').parents().eq(1);
    parent_of_pac_drh_desktop_preset_three = jQuery('#pac_drh_desktop_preset_three').parents().eq(1);
    parent_of_enabel_text_sizes = jQuery('#pac_drh_enable_text_sizes').parents().eq(1);
    jQuery(pac_drh_widow_options());
    jQuery(pac_drh_presets());
    jQuery(pac_drh_text_sizes());
    setTimeout(function() {
        yes_no_button_of_enable_widow_fixer = jQuery(parent_of_enable_widow_fixer).find('.et_pb_yes_no_button');
        yes_no_button_of_paragraph_widow_fixer = jQuery(parent_of_paragraph_widow_fixer).find('.et_pb_yes_no_button');
        yes_no_button_of_heading_widow_fixer = jQuery(parent_of_widwo_fixer_headings).find('.et_pb_yes_no_button');
        yes_no_button_to_enable_custom_presets = jQuery(parent_of_yes_no_button_to_enable_custom_presets).find('.et_pb_yes_no_button');
        yes_no_button_of_pages_widow_fixer = jQuery(parent_of_pages_widow_fixer).find('.et_pb_yes_no_button');
        yes_no_button_of_enable_text_sizes = jQuery(parent_of_enabel_text_sizes).find('.et_pb_yes_no_button');
        jQuery(yes_no_button_of_enable_widow_fixer).on('click', function() {
            setTimeout(function() {
                jQuery(pac_drh_widow_options());
            }, 100);
        });
        jQuery(yes_no_button_of_heading_widow_fixer).on('click', function() {
            setTimeout(function() {
                jQuery(pac_drh_widow_options());
            }, 100);
        });
        jQuery(yes_no_button_of_paragraph_widow_fixer).on('click', function() {
            setTimeout(function() {
                jQuery(pac_drh_widow_paragraph_option());
            }, 100);
        });
        jQuery(yes_no_button_of_pages_widow_fixer).on('click', function() {
            setTimeout(function() {
                jQuery(pac_drh_widow_pages_option());
            }, 100);
        });
        jQuery(yes_no_button_to_enable_custom_presets).on('click', function() {
            setTimeout(function() {
                jQuery(pac_drh_presets());
            }, 100);
        });
        jQuery(yes_no_button_of_enable_text_sizes).on('click', function() {
            setTimeout(function() {
                jQuery(pac_drh_text_sizes());
            }, 100);
        });
    }, 1000);
});

function pac_drh_widow_options() {
    if (jQuery('#pac_drh_enable_widow_fixer').is(':checked')) {
        jQuery(parent_of_paragraph_widow_fixer).show();
        jQuery(parent_of_widwo_fixer_headings).show();
        jQuery(pac_drh_widow_paragraph_option());
        jQuery(pac_drh_widow_heading_option());
        //pages
        jQuery(parent_of_pages_widow_fixer).show();
        jQuery(pac_drh_widow_pages_option());
    } else {
        jQuery(parent_of_paragraph_widow_fixer).hide();
        jQuery(parent_of_widwo_fixer_headings).hide();
        jQuery(parent_of_paragraph_select_widow_select).hide();
        jQuery(parent_of_heading_select_widow_select).hide();
        // Pages
        jQuery(parent_of_pages_widow_fixer).hide();
        jQuery(parent_of_pages_list_widow_fixer).hide();
    }
}

function pac_drh_widow_paragraph_option() {
    if (jQuery('#pac_drh_enable_paragraph_widow_fixer').is(':checked')) {
        jQuery(parent_of_paragraph_select_widow_select).show();
    } else {
        jQuery(parent_of_paragraph_select_widow_select).hide();
    }
}

function pac_drh_widow_heading_option() {
    if (jQuery('#pac_drh_widow_fixer_headings').is(':checked')) {
        jQuery(parent_of_heading_select_widow_select).show();
    } else {
        jQuery(parent_of_heading_select_widow_select).hide();
    }
}

function pac_drh_widow_pages_option() {
    if (jQuery('#pac_drh_enable_pages_widow_fixer').is(':checked')) {
        jQuery(parent_of_pages_list_widow_fixer).show();
    } else {
        jQuery(parent_of_pages_list_widow_fixer).hide();
    }
}

function pac_drh_presets() {
    if (jQuery('#pac_drh_enable_presets').is(':checked')) {
        jQuery(parent_of_pac_drh_phone_preset_one).show();
        jQuery(parent_of_pac_drh_phone_preset_two).show();
        jQuery(parent_of_pac_drh_phone_preset_three).show();
        jQuery(parent_of_pac_drh_tablet_preset_one).show();
        jQuery(parent_of_pac_drh_tablet_preset_two).show();
        jQuery(parent_of_pac_drh_tablet_preset_three).show();
        jQuery(parent_of_pac_drh_desktop_preset_one).show();
        jQuery(parent_of_pac_drh_desktop_preset_two).show();
        jQuery(parent_of_pac_drh_desktop_preset_three).show();
    } else {
        jQuery(parent_of_pac_drh_phone_preset_one).hide();
        jQuery(parent_of_pac_drh_phone_preset_two).hide();
        jQuery(parent_of_pac_drh_phone_preset_three).hide();
        jQuery(parent_of_pac_drh_tablet_preset_one).hide();
        jQuery(parent_of_pac_drh_tablet_preset_two).hide();
        jQuery(parent_of_pac_drh_tablet_preset_three).hide();
        jQuery(parent_of_pac_drh_desktop_preset_one).hide();
        jQuery(parent_of_pac_drh_desktop_preset_two).hide();
        jQuery(parent_of_pac_drh_desktop_preset_three).hide();
    }
}

function pac_drh_text_sizes() {
    var devicesArr = ['desktop', 'tablet', 'phone']
    if (jQuery('#pac_drh_enable_text_sizes').is(':checked')) {
        for (var checked = 0; checked < devicesArr.length; checked++) {
            var checkedDevice = devicesArr[checked];
            for (var checkedHeading = 1; checkedHeading <= 6; checkedHeading++) {
                jQuery('#pac_drh_h' + checkedHeading + '_' + checkedDevice).parents().eq(1).show()
            }
            jQuery('#pac_drh_p_' + checkedDevice).parents().eq(1).show()
        }
    } else {
        for (var unChecked = 0; unChecked < devicesArr.length; unChecked++) {
            var unCheckedDevice = devicesArr[unChecked];
            for (var uncheckedHeading = 1; uncheckedHeading <= 6; uncheckedHeading++) {
                jQuery('#pac_drh_h' + uncheckedHeading + '_' + unCheckedDevice).parents().eq(1).hide()
            }
            jQuery('#pac_drh_p_' + unCheckedDevice).parents().eq(1).hide()
        }
    }
}