window.pac_drh_custom_width = '0';
(function ($) {
    'use strict';

    $(document).ready(function () {
        const htmlObj = $('html');

        const iframeSelector = 'iframe';

        var isButtonsAdded = false;

        function addPreviewButtons(size) {

            if (isButtonsAdded) {
                return;
            }

            isButtonsAdded = true;

            const settingsBarObj = $('.et-fb-page-settings-bar .et-fb-page-settings-bar__column.et-fb-page-settings-bar__column--left' +
                ' .et-fb-button-group:first-of-type');

            if ('phone' === size) {
                if ((pac_drh_custom_presets.pac_drh_phone_preset_one > 300) && (pac_drh_custom_presets.pac_drh_phone_preset_one < 768)) {
                    settingsBarObj.append(
                        `<button type="button" data-size="xs" class="et-fb-button et-fb-button--inverse et-fb-button--app-modal pac_preset_preview_button pac_preset">` +
                        pac_drh_custom_presets.pac_drh_phone_preset_one +
                        `</button>`
                    );
                }

                if ((pac_drh_custom_presets.pac_drh_phone_preset_two > 300) && (pac_drh_custom_presets.pac_drh_phone_preset_two < 768)) {
                    settingsBarObj.append(
                        `<button type="button" data-size="l" class="et-fb-button et-fb-button--inverse et-fb-button--app-modal pac_preset_preview_button pac_preset">` +
                        pac_drh_custom_presets.pac_drh_phone_preset_two +
                        `</button>`
                    );
                }

                if ((pac_drh_custom_presets.pac_drh_phone_preset_three > 300) && (pac_drh_custom_presets.pac_drh_phone_preset_three < 768)) {
                    settingsBarObj.append(
                        `<button type="button" data-size="xl" class="et-fb-button et-fb-button--inverse et-fb-button--app-modal pac_preset_preview_button pac_preset">` +
                        pac_drh_custom_presets.pac_drh_phone_preset_three +
                        `</button>`
                    );
                }
                $('button.pac_preset_preview_button[data-size="l"]').css('color', 'rgb(112, 195, 169)');
                $('button.pac_preset_preview_button[data-size="l"]').addClass('pac_drh_active');

            } else if ('tablet' === size) {
                if ((pac_drh_custom_presets.pac_drh_tablet_preset_one > 767) && (pac_drh_custom_presets.pac_drh_tablet_preset_one < 982)) {
                    settingsBarObj.append(
                        `<button type="button" data-size="s" class="et-fb-button et-fb-button--inverse et-fb-button--app-modal pac_preset_preview_button pac_preset">` +
                        pac_drh_custom_presets.pac_drh_tablet_preset_one +
                        `</button>`
                    );
                }

                if ((pac_drh_custom_presets.pac_drh_tablet_preset_two > 767) && (pac_drh_custom_presets.pac_drh_tablet_preset_two < 982)) {
                    settingsBarObj.append(
                        `<button type="button" data-size="m" class="et-fb-button et-fb-button--inverse et-fb-button--app-modal pac_preset_preview_button pac_preset">` +
                        pac_drh_custom_presets.pac_drh_tablet_preset_two +
                        `</button>`
                    );
                }

                if ((pac_drh_custom_presets.pac_drh_tablet_preset_three > 767) && (pac_drh_custom_presets.pac_drh_tablet_preset_three < 982)) {
                    settingsBarObj.append(
                        `<button type="button" data-size="l" class="et-fb-button et-fb-button--inverse et-fb-button--app-modal pac_preset_preview_button pac_preset">` +
                        pac_drh_custom_presets.pac_drh_tablet_preset_three +
                        `</button>`
                    );
                }
                $('button.pac_preset_preview_button[data-size="s"]').css('color', 'rgb(112, 195, 169)');
                $('button.pac_preset_preview_button[data-size="s"]').addClass('pac_drh_active');

            } else if ('desktop' === size) {
                if (pac_drh_custom_presets.pac_drh_desktop_preset_one > 980) {
                    settingsBarObj.append(
                        `<button type="button" data-size="s" class="et-fb-button et-fb-button--inverse et-fb-button--app-modal pac_preset_preview_button pac_preset">` +
                        pac_drh_custom_presets.pac_drh_desktop_preset_one +
                        `</button>`
                    );
                }

                if (pac_drh_custom_presets.pac_drh_desktop_preset_two > 980) {
                    settingsBarObj.append(
                        `<button type="button" data-size="m" class="et-fb-button et-fb-button--inverse et-fb-button--app-modal pac_preset_preview_button pac_preset">` +
                        pac_drh_custom_presets.pac_drh_desktop_preset_two +
                        `</button>`
                    );
                }

                if (pac_drh_custom_presets.pac_drh_desktop_preset_three > 980) {
                    settingsBarObj.append(
                        `<button type="button" data-size="l" class="et-fb-button et-fb-button--inverse et-fb-button--app-modal pac_preset_preview_button pac_preset">` +
                        pac_drh_custom_presets.pac_drh_desktop_preset_three +
                        `</button>`
                    );
                }
            }
            settingsBarObj.append(
                `<input type="text" data-size="custom" class="et-fb-button et-fb-button--inverse et-fb-button--app-modal pac_preset_preview_button pac_set_preview_size_input_filed" name="pac_preview_size" value="" placeholder="Enter Size" />`
            );
        }

        function removePreviewButtons() {
            $('.pac_preset_preview_button').remove();
            isButtonsAdded = false;
        }

        function isPhonePreview() {
            if ($('#wpwrap').length > 0) {
                return $(iframeSelector).contents().find("html").hasClass("et-fb-preview--phone") || $(iframeSelector).contents().find("html").hasClass('et_fb_preview_active--responsive_preview--phone_preview');
            }
            return (htmlObj.hasClass('et_fb_preview_active--responsive_preview--phone_preview') || htmlObj.hasClass('et-fb-preview--phone'));
        }

        function isTabletPreview() {
            if ($('#wpwrap').length > 0) {
                return $(iframeSelector).contents().find("html").hasClass("et-fb-preview--tablet") || $(iframeSelector).contents().find("html").hasClass('et_fb_preview_active--responsive_preview--tablet_preview');
            }
            return (htmlObj.hasClass('et_fb_preview_active--responsive_preview--tablet_preview') || htmlObj.hasClass('et-fb-preview--tablet'));
        }

        function isDesktopPreview() {
            if ($('#wpwrap').length > 0) {
                return $(iframeSelector).contents().find("html").hasClass("et-fb-preview--desktop") || $(iframeSelector).contents().find("html").hasClass('et_fb_preview_active--responsive_preview--desktop_preview');
            }
            return (htmlObj.hasClass('et_fb_preview_active--responsive_preview--desktop_preview') || htmlObj.hasClass('et-fb-preview--desktop'));
        }

        const MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
        let isThemeBuilder = false;
        if ($('#et-theme-builder').length > 0) {
            isThemeBuilder = true;
        }
        if (!isThemeBuilder) {
            var pfObserver = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (isPhonePreview()) {
                        removePreviewButtons();
                        addPreviewButtons('phone');
                    } else if (isTabletPreview()) {
                        removePreviewButtons();
                        addPreviewButtons('tablet');
                    } else if (isDesktopPreview()) {
                        removePreviewButtons();
                        addPreviewButtons('desktop');
                    } else {
                        removePreviewButtons();
                    }
                });
            });
            pfObserver.observe(htmlObj.get(0), {attributes: true});
        } else {
            var builderObserver = new MutationObserver(function (mutations) {
                if ($(".et-fb-page-settings-bar .et-fb-page-settings-bar__column.et-fb-page-settings-bar__column--left .et-fb-button-group:first-of-type").length) {
                    if (isPhonePreview()) {
                        console.log('isTabletPreview');
                        removePreviewButtons();
                        addPreviewButtons('phone');
                        builderObserver.disconnect();
                    } else if (isTabletPreview()) {
                        console.log('isTabletPreview');
                        removePreviewButtons();
                        addPreviewButtons('tablet');
                        builderObserver.disconnect();
                    } else if (isDesktopPreview()) {
                        console.log('isDesktopPreview');
                        removePreviewButtons();
                        addPreviewButtons('desktop');
                        builderObserver.disconnect();
                    } else {
                        removePreviewButtons();
                        builderObserver.disconnect();
                    }
                }
            });

            builderObserver.observe(htmlObj.get(0), {
                attributes: true,
                subtree: true,
                childList: true,
            });

            jQuery(document).on('DOMNodeInserted', 'head', function () {
                builderObserver.observe(htmlObj.get(0), {
                    attributes: true,
                    subtree: true,
                    childList: true,
                });
            });

        }

        $('body').on('click', '.pac_preset_preview_button', (e) => {
            $('.pac_preset_preview_button').css('color', '');
            $('.pac_preset_preview_button').removeClass('pac_drh_active');
            $(e.currentTarget).css('color', 'rgb(112, 195, 169)');
            var pac_drh_current_active_value = $(e.currentTarget).text();
            if (pac_drh_current_active_value) {
                var width = pac_drh_current_active_value;
                jQuery('.pac_set_preview_size_input_filed').val(pac_drh_current_active_value);
            }
            var custom_value = jQuery('.pac_set_preview_size_input_filed').val();
            if (custom_value) {
                width = custom_value;
            }
            $(e.currentTarget).addClass('pac_drh_active');
            var containerSelector = '';
            if (isPhonePreview()) {
                containerSelector = '.et_fb_preview_active--responsive_preview--phone_preview .et_fb_preview_container';
            }
            if (isTabletPreview()) {
                containerSelector = '.et_fb_preview_active--responsive_preview--tablet_preview .et_fb_preview_container';
            }
            if (isDesktopPreview()) {
                containerSelector = '.et_fb_preview_active--responsive_preview--desktop_preview .et_fb_preview_container';
            }

            jQuery('.pac_set_preview_size_input_filed').on('keyup change click', function (e) {
                width = jQuery('.pac_set_preview_size_input_filed').val();
                $(containerSelector).width(width);
                if (isBackendBuilder()) {
                    $('#et-bfb-app-frame').width(width);
                } else {
                    $('#et-fb-app-frame').width(width);
                }

                var pacCurrentActiveTab = jQuery('button.pac_preset_preview_button');
                pacCurrentActiveTab.each(function () {
                    var pac_drh_button_size = jQuery(this).text();
                    var pac_current_input = jQuery('.pac_set_preview_size_input_filed').val();
                    if (pac_drh_button_size === pac_current_input) {
                        jQuery(this).css('color', 'rgb(112, 195, 169)');
                    } else {
                        jQuery(this).css('color', '');
                    }
                });
            });

            jQuery('.pac_set_preview_size_input_filed').mouseleave(function () {
                setTimeout(function () {
                    var pac_new_preview_size = jQuery('.pac_set_preview_size_input_filed').val();
                    if ((pac_new_preview_size > 0) && (pac_new_preview_size < 768)) {
                        if (isTabletPreview() || isDesktopPreview()) {
                            jQuery('.et-fb-icon--phone').click();
                            setTimeout(function () {
                                jQuery('.pac_set_preview_size_input_filed').val(pac_new_preview_size).click();
                            }, 1200);
                        }
                    }
                    if ((pac_new_preview_size > 767) && (pac_new_preview_size < 982)) {
                        if (isPhonePreview() || isDesktopPreview()) {
                            jQuery('.et-fb-icon--tablet').click();
                            setTimeout(function () {
                                jQuery('.pac_set_preview_size_input_filed').val(pac_new_preview_size).click();
                            }, 1200);
                        }
                    }
                    if (pac_new_preview_size > 980) {
                        // if not Desktop
                        if (isPhonePreview() || isTabletPreview()) {
                            jQuery('.et-fb-icon--desktop').click();
                            setTimeout(function () {
                                jQuery('.pac_set_preview_size_input_filed').val(pac_new_preview_size).click();
                            }, 1200);
                        }
                    }
                    jQuery('.pac_set_preview_size_input_filed').blur();
                }, 1000);
            });

            $(containerSelector).width(width);

            var bodyWidth = $("body").width();
            var etAppFrame = '#et-fb-app-frame';
            var etFBModal = '.et-fb-modal';
            var etModalWidth = $(etFBModal).width();
            var isFrontend = true;
            if ($('#et-bfb-app-frame').length > 0) {
                etAppFrame = '#et-bfb-app-frame'
                isFrontend = false;
            }
            var etModalLeft = $(etFBModal).hasClass("et-fb-modal--snapped-left");
            var etModalRight = $(etFBModal).hasClass("et-fb-modal--snapped-right");
            if (isFrontend) {
                if (etModalLeft || etModalRight) {
                    var translate = (bodyWidth - etModalWidth - width) / 2;
                    var operator = etModalLeft ? "-" : "";
                    if (width !== '1200') {
                        $(etAppFrame).css({
                            'width': width,
                            'transform': 'translate(' + operator + translate + 'px)'
                        });
                    } else {
                        $(etAppFrame).css({
                            'width': bodyWidth - etModalWidth,
                            'transform': 'scale(1) translate(0px)'
                        });
                    }
                } else {
                    $(etAppFrame).width(width);
                }
            } else {
                $(etAppFrame).width(width);
            }
        });
    });

    function isBackendBuilder() {
        if (jQuery('.et-bfb').length) {
            return true;
        } else {
            return false;
        }
    }
})(jQuery);