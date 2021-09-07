jQuery(document).ready(function ($) {
    var pac_drh_editors = [];

    function pac_drh_add_instance(codeEditor, $element, config) {
        if (!$element || $element.length === 0) {
            return;
        }
        var instance = codeEditor.initialize($element, {
            codemirror: config,
        });
        if (instance && instance.codemirror) {
            pac_drh_editors.push(instance.codemirror);
        }
    }

    var pac_drh_code = window.wp && window.wp.codeEditor;
    if (pac_drh_code && pac_drh_code.initialize && pac_drh_code.defaultSettings && pac_drh_code.defaultSettings.codemirror) {
        var pac_drh_config_css = $.extend({}, pac_drh_code.defaultSettings.codemirror, {
            theme: 'et',
        });
        pac_drh_add_instance(pac_drh_code, $('#pac_drh_desktop_media_query'), pac_drh_config_css);
        pac_drh_add_instance(pac_drh_code, $('#pac_drh_tablet_media_query'), pac_drh_config_css);
        pac_drh_add_instance(pac_drh_code, $('#pac_drh_phone_media_query'), pac_drh_config_css);
        pac_drh_add_instance(pac_drh_code, $('#pac_drh_desktop_tablet_media_query'), pac_drh_config_css);
        pac_drh_add_instance(pac_drh_code, $('#pac_drh_tablet_phone_media_query'), pac_drh_config_css);
    }

    $('#epanel-save-top').click(function (e) {
        e.preventDefault();
        $('#epanel-save').trigger('click');
    })

    $('#epanel-save').click(function () {
        pac_drh_epanel_save(false, true);
        return false;
    });

    function pac_drh_epanel_save() {
        if (pac_drh_editors.length > 0) {
            $.each(pac_drh_editors, function (i, editor) {
                if (editor.save) {
                    editor.save();
                }
            })
        }
        var pac_drh_options = $('#main_options_form').formSerialize(),
            add_nonce = '&_ajax_nonce=' + ePanelSettings.epanel_nonce;
        pac_drh_options += add_nonce;
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: pac_drh_options,
        });
    }

});

