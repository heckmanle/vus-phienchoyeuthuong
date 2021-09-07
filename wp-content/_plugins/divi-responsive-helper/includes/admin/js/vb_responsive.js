window.pac_set_preview_value = '';
window.pac_device_list_element = 'ul.et-fb-settings-tab-titles';
window.Desktop = 'Desktop';
window.Tablet = 'Tablet';
window.Phone = 'Phone';
jQuery(document).ready(function () {
    jQuery(document).on('DOMNodeInserted', '.et-fb-modal__module-settings .et-fb-form__toggle-title', function () {
        setTimeout(function () {
            var pacResponsiveElement = jQuery('span.et-fb-form__responsive');

            pacResponsiveElement.each(function () {
                var ignoreTab = '';
                ignoreTab = jQuery(this).parent().eq(0).text().toLowerCase().replace(/\s/g, "");
                if ('body' === ignoreTab
                    || 'title' === ignoreTab
                    || 'image' === ignoreTab
                    || 'videowebmfile' === ignoreTab
                ) {
                    // do nothing
                } else {
                    var pacAlreadyClicked = jQuery(this).hasClass('et-fb-form__responsive--active');
                    if (!pacAlreadyClicked && 'on' === pac_drh_vb_obj.responsiveTabs) {
                        jQuery('.et-fb-form__responsive').click();
                    } else {
                        // do nothing
                    }
                }

            });

            jQuery(pac_device_list_element).each(function () {
                if (jQuery(this).children().length === 3) {
                    Desktop = jQuery(this).find('li:nth-child(1)').text();
                    Tablet = jQuery(this).find('li:nth-child(2)').text();
                    Phone = jQuery(this).find('li:nth-child(3)').text();
                } else {
                    // Do nothing
                }
            });
            // if Toggle Title is found
            if (jQuery('.et-fb-form__toggle-title').length) {
                jQuery(pac_toggle_title_click);
            }

            // if yes-no button is found
            if (jQuery('.et-fb-option--yes-no_button').length) {
                jQuery(pac_yes_or_button_click);
            }

            // if subbtoggle is found
            if (jQuery('.et-fb-subtoggle-tab').length) {
                jQuery(pac_subtoggle_button_click);
            }

            // set preview size without any focus value
            if (jQuery('button.et-fb-settings-tab-title-active').length) {
                jQuery('button.et-fb-settings-tab-title-active').trigger('click', true);
            }
        }, 1000);
    });
});


function pac_toggle_title_click() {
    jQuery('.et-fb-form__toggle-title').on('click', function () {
        setTimeout(function () {
            jQuery('button.et-fb-settings-tab-title-active').trigger('click', true);
        }, 1000);
    });
}

function pac_yes_or_button_click() {
    jQuery('.et-fb-option--yes-no_button').on('click', function () {
        setTimeout(function () {
            var pacResponsiveElement = jQuery('span.et-fb-form__responsive');
            pacResponsiveElement.each(function () {
                var pacAlreadyClicked = jQuery(this).hasClass('et-fb-form__responsive--active');
                if (pacAlreadyClicked) {
                    // do nothing
                } else {
                    jQuery('.et-fb-form__responsive').click();
                }
            });
            jQuery('button.et-fb-settings-tab-title-active').trigger('click', true);
        }, 100);
    });
}

function pac_subtoggle_button_click() {
    jQuery('.et-fb-subtoggle-tab').on('click', function () {
        setTimeout(function () {
            var pacResponsiveElement = jQuery('span.et-fb-form__responsive');
            pacResponsiveElement.each(function () {
                var pacAlreadyClicked = jQuery(this).hasClass('et-fb-form__responsive--active');
                if (pacAlreadyClicked) {
                    // do nothing
                } else {
                    jQuery('.et-fb-form__responsive').click();
                }
            });
        }, 100);
        jQuery('button.et-fb-settings-tab-title-active').trigger('click', true);
    });
}
