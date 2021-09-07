var Applications = {};
jQuery(function($){
    $.helpers = {
        getToastrOption (){
            return {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        },
        actionAjax (params) {
            const { method, data, async, dataType, funcBeforeSend, funcError, funcSuccess, funcComplete, args } = params;
            let dataSend = data;
            if( args !== undefined && args.id.length ){
                dataSend = $.extend(data, args, {});
            }
            let options = {
                url: AJAX_URL,
                type: method || 'post',
                dataType: dataType || 'json',
                async: async || true,
                data: dataSend,
                beforeSend: function(xhr, settings) {
                    if (typeof funcBeforeSend === 'function') {
                        funcBeforeSend.apply(null, [xhr, settings]);
                    }else{
                        $('body').block(IMAGE_LOADING);
                    }
                    return;
                },
                error: function(xhr, status, errThrow) {
                    if (typeof funcError === 'function') {
                        funcError.apply(null, [xhr, status, errThrow]);
                    }
                    return;
                },
                success: function(response, status, xhr) {
                    if (typeof funcSuccess === 'function') {
                        funcSuccess.apply(null, [response, status, xhr]);
                    }
                    return;
                },
                complete: function(xhr, status) {
                    if (typeof funcComplete === 'function') {
                        funcComplete.apply(null, [xhr, status]);
                    } else {
                        $('body').unblock(IMAGE_LOADING);
                    }
                    return;
                }
            };
            return $.ajax(options);
        },
        bindEventDialogConfirm (element, callback, ...params){
            const self = this;
            let $dialogConfirm = jQuery(element);
            let callbackYes = callback, callbackNo;
            let {yes, no} = callback;
            if( typeof yes === 'function' ){
                callbackYes = yes;
            }
            $dialogConfirm.off('hidden.bs.modal.confirm').on('hidden.bs.modal.confirm', function(ev){
                let data = $('.modal-title', $dialogConfirm).data();
                let {title} = data;
                if( title !== undefined ){
                    $('.modal-title', $dialogConfirm).html(title);
                }
                $dialogConfirm.off('click.dialog.agree', element + ' .btn-no');
                if(typeof no === 'function') {
                    no.apply(self, params);
                }
            });
            $dialogConfirm.off('show.bs.modal.confirm').on('show.bs.modal.confirm', function(ev){
                $dialogConfirm.off('click.dialog.agree', '.btn-yes').on('click.dialog.agree', '.btn-yes', function(event){
                    event.preventDefault();
                    if(typeof callbackYes === 'function'){
                        callbackYes.apply(self, params);
                    }
                    return this;
                });
            });
            $dialogConfirm.modal();
            return this;
        },
        confirmRemoveItemRepeater (deleteElement, modal) {
            const removeElement = () => {
                $(this).slideUp(deleteElement);
                $(modal).modal('hide');
            }
            $(modal).find('.modal-title').html('Are you sure you want to delete this element?');
            this.bindEventDialogConfirm(modal, removeElement);
        },
        _getUID (prefix) {
            do {
                prefix += ~~(Math.random() * 1000000);
            } while (document.getElementById(prefix));

            return prefix;
        },
        createModal (args) {
            let defaults = {
                id: 'createModal',
                title: '',
                body: true,
                bodyHtml: '',
                modalClass: '',
                header: true,
                footer: true,
                yesButton: true,
                yesButtonText: 'Đồng ý',
                noButton: true,
                noButtonText:  'Huỷ',
                footerAdditionalHtml: '',
                funcYes: null,
                funcNo: null,
                args: []
            };
            let data = $.extend(defaults, args),
                header = '', body = '', footer = '';
            if( data.header ) {
                header = '<div class="modal-header">\n' +
                    '<button type="button" class="close" data-dismiss="modal"><span>×</span></button>\n' +
                    '<h4 class="modal-title" data-title="' + data.title + '">' + data.title + '</h4>\n' +
                    '</div>\n';
            }
            if( data.body ) {
                body = '<div class="modal-body">' + data.bodyHtml + '</div>';
            }
            if( data.footer ) {
                let yesButton = '', noButton = '', additionalHtml = '';
                if( data.yesButton ){
                    yesButton = '<button type="button" class="btn btn-default btn-primary btn-yes font-weight-500 modal-btn-action">' + data.yesButtonText + '</button>\n';
                }
                if( data.noButton ){
                    noButton = '<button type="button" data-dismiss="modal" class="btn btn-default btn-close font-weight-500 btn-no">' + data.noButtonText + '</button>';
                }
                if( data.footerAdditionalHtml.length ){
                    additionalHtml = data.footerAdditionalHtml;
                }
                footer = '<div class="modal-footer">\n' +
                    noButton + yesButton + additionalHtml
                    '</div>\n';
            }
            let modalID = '#' + data.id;
            let modal = '<div id="' + data.id + '" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog">\n' +
                '    <div class="modal-dialog ' + data.modalClass + '">\n' +
                '        <div class="modal-content">\n'
                + header + body + footer +
                '        </div>\n' +
                '    </div>\n' +
                '</div>';
            if( !$('body').find(modalID).length ){
                $('body').append(modal);
            }

            this.bindEventDialogConfirm(modalID, {yes: data.funcYes, no: data.funcNo}, data.args);
        },
        hasData (t) {
            return "undefined" != typeof $(this).data(t)
        },
        getDataOptions (t, e) {
            if (e || (e = "options"), this.hasData(t)) {
                var i = $.extend({}, t.data());
                return i.options ? i.options : null
            }
            return null
        },
        renderAlert (message, type = 'error'){
            let classes_alert = '';
            switch (type){
                case 'success':
                    classes_alert = 'alert-success';
                    break;
                case 'warning':
                    classes_alert = 'alert-warning';
                    break;
                case 'error':
                default:
                    classes_alert = 'alert-danger';
                    break;
            }
            return '<div class="alert ' + classes_alert + ' alert-dismissible" role="alert">\n' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n' +
                '<div class="message-response notification">' +
                     message +
                '</div>\n' +
                '</div>';
        },
        convertStringToMoney (val, decimal = NUM_DECIMAL) {
            return this.formatMoney( val,  decimal, DECIMAL_SEP, THOUNSAND_SEP);
        },
        convertMoneyToNumber (val) {
            if ( typeof val !== "string" ) {
                val = val.toString();
            }

            val = val.replaceAll(THOUNSAND_SEP, '').replace(DECIMAL_SEP, '.');

            return Number(val);
        },
        formatMoney (n, c, d, t) {
            var c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? THOUNSAND_SEP : d,
                t = t == undefined ? DECIMAL_SEP : t,
                s = n < 0 ? "-" : "",
                i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                j = (j = i.length) > 3 ? j % 3 : 0;

            let val = s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
            let decimal = val.split(DECIMAL_SEP);
            if( decimal.length > 1 ){
                let decimal_1 = decimal[1];
                if( Number(decimal_1) === 0 ){
                    val = decimal[0];
                }
            }
            return val;
        },
        settingUnderScore () {
            _.templateSettings = {
                evaluate: /<#([\s\S]+?)#>/g,
                interpolate: /{{{([\s\S]+?)}}}/g,
                escape: /{{([^}]+?)}}(?!})/g
            };
        },
        getOptionSpectrum(){
            return {
                color: false,
                flat: false, // Deprecated - use type instead
                type: 'text', // text, color, component or flat
                showInput: false,
                allowEmpty: true,
                showButtons: true,
                clickoutFiresChange: true,
                showInitial: false,
                showPalette: true,
                showPaletteOnly: false,
                hideAfterPaletteSelect: false,
                togglePaletteOnly: false,
                showSelectionPalette: true,
                localStorageKey: false,
                appendTo: "body",
                maxSelectionSize: 8,
                locale: "vi",
                cancelText: boardTrans._t('Huỷ'),
                chooseText: boardTrans._t('Chọn'),
                togglePaletteMoreText: boardTrans._t('Mở rộng'),
                togglePaletteLessText: boardTrans._t('Thu gọn'),
                clearText: boardTrans._t('Huỷ màu chọn'),
                noColorSelectedText: boardTrans._t('Không có màu được chọn'),
                preferredFormat: "name",
                className: "", // Deprecated - use containerClassName and replacerClassName instead.
                containerClassName: "",
                replacerClassName: "",
                showAlpha: true,
                theme: "sp-light",
                palette: [
                    ["#000000","#444444","#5b5b5b","#999999","#bcbcbc","#eeeeee","#f3f6f4","#ffffff"],
                    ["#f44336","#744700","#ce7e00","#8fce00","#2986cc","#16537e","#6a329f","#c90076"],
                    ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
                    ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
                    ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
                    ["#cc0000","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
                    ["#990000","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
                    ["#660000","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
                ],
                selectionPalette: [],
                disabled: false,
                offset: null
            }
        },
        initSelect2LocaleVi() {
            $.fn.select2.amd.define('select2/i18n/vi',[],function () {
                return {
                    inputTooLong:function(n){return"Vui lòng xóa bớt "+(n.input.length-n.maximum)+" ký tự"},inputTooShort:function(n){return"Vui lòng nhập thêm từ "+(n.minimum-n.input.length)+" ký tự trở lên"},loadingMore:function(){return"Đang lấy thêm kết quả…"},maximumSelected:function(n){return"Chỉ có thể chọn được "+n.maximum+" lựa chọn"},noResults:function(){return"Không tìm thấy kết quả"},searching:function(){return"Đang tìm…"},removeAllItems:function(){return"Xóa tất cả các mục"}
                }
            });
        },
        getContrastColor (hexcolor){
            hexcolor = hexcolor.replace("#", "");
            var r = parseInt(hexcolor.substr(0,2),16);
            var g = parseInt(hexcolor.substr(2,2),16);
            var b = parseInt(hexcolor.substr(4,2),16);
            var color = (( r*299) + (g*587) + (b*114) ) / 1000;
            return (color >= 128) ? 'black' : 'white';
        },
        encodeHtml(str){
            var buf = [];

            for (var i=str.length-1;i>=0;i--) {
                buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
            }

            return buf.join('');
        },
        decodeHtml(str){
            return str.replace(/&#(\d+);/g, function(match, dec) {
                return String.fromCharCode(dec);
            });
        },
        getTextMessageByXHR (xhr, errThrow){
            let textError = '';
            if( xhr.hasOwnProperty('responseJSON') && xhr.responseJSON.hasOwnProperty( 'message' ) && (xhr.responseJSON.message != '') ){
                textError = xhr.responseJSON.message;
            }else if( errThrow != '' ){
                textError = xhr.getResponseHeader('xhr-message') || errThrow;
                try{
                    textError = JSON.parse(textError);
                } catch(e){
                    textError = 'Đã có lỗi xảy ra, vui lòng thử lại';
                }
            }else{
                textError = 'Đã có lỗi xảy ra, vui lòng thử lại';
            }
            return textError;
        },
        translateValidate () {
            $.extend($.validator, {
                messages: {
                    required: MESSAGES_VALIDATORS.required,
                    remote: MESSAGES_VALIDATORS.remote,
                    email: MESSAGES_VALIDATORS.email,
                    url: MESSAGES_VALIDATORS.url,
                    date: MESSAGES_VALIDATORS.date,
                    dateISO: MESSAGES_VALIDATORS.dateISO,
                    number: MESSAGES_VALIDATORS.number,
                    digits: MESSAGES_VALIDATORS.digits,
                    equalTo: MESSAGES_VALIDATORS.equalTo,
                    maxlength: $.validator.format( MESSAGES_VALIDATORS.maxlength ),
                    minlength: $.validator.format( MESSAGES_VALIDATORS.minlength ),
                    rangelength: $.validator.format( MESSAGES_VALIDATORS.rangelength ),
                    range: $.validator.format( MESSAGES_VALIDATORS.range ),
                    max: $.validator.format( MESSAGES_VALIDATORS.max ),
                    min: $.validator.format( MESSAGES_VALIDATORS.min ),
                    step: $.validator.format( MESSAGES_VALIDATORS.step )
                }
            });
        },
    }
    Applications.helpers = $.helpers;
});
