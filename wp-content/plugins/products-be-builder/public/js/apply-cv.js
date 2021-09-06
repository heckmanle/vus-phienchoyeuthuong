jQuery(function ($){
    'use strict';
    _.templateSettings = {
        evaluate: /<#([\s\S]+?)#>/g,
        interpolate: /{{{([\s\S]+?)}}}/g,
        escape: /{{([^}]+?)}}(?!})/g
    };
    $.REP_APPLY_CV = {
        init () {
            this.$el = $('.apply-cv-wrapper');
            this.$templateExpItem = $('script#tpl-exp-item');
            this.$templateElvItem = $('script#tpl-elv-item');
            this.backbone();
        },
        backbone() {
            const self = this;
            let _rep_apply_cv = Backbone.View.extend({
                el: self.$el,
                template: {
                    exp: _.template(self.$templateExpItem.html()),
                    elv: _.template(self.$templateElvItem.html()),
                },
                events: {
                    'change .material-label-upload input[name="file_cv"]': 'eventUploadFile',
                    'click .btn-apply-action-add': 'eventRepeaterAdd',
                    'click .btn-apply-action-remove': 'eventRepeaterRemove',
                },
                initialize() {
                    this.initForm();
                },
                initForm () {
                    const _self = this;
                    $('form.form-apply-cv').each(function(idx, ele){
                        _self.formValidate($(this));
                    });
                },
                formValidate ($form) {
                    const _self = this;
                    let $validation = $form.find('[data-is-validation="true"]');
                    let validationRules = {
                        focusCleanup: false,
                        errorClass: 'error',
                        validClass: 'valid',
                        rules: {},
                        // Specify validation error messages
                        messages: {},
                        errorElement: "em",
                        errorPlacement: function ( error, $element ) {
                            // Add the `help-block` class to the error element
                            error.addClass( "help-block" );
                            if('$label' in $element[0]) {
                                error.insertAfter( $element[0].$label );
                            } else if ( $element.prop( "type" ) === "checkbox" ) {
                                error.insertAfter( $element.parent( "label" ) );
                            } else {
                                error.insertAfter( $element );
                            }
                            return false;
                        },
                        invalidHandler: function(ev) {
                            var validator = $form.data('validator');
                            var elements = validator.invalidElements();

                            if( validator.errorList.length == 0 ) {
                                validator.errorList = [];
                                _self.formValidate();
                                $form.trigger('submit.validate');
                                return;
                            }
                            return;
                        },
                        submitHandler: function(form, ev) {
                            var $form = $(form);
                            var validator = this;
                            return _self.formSubmit($form, validator, ev);
                        }
                    }
                    $validation.each(function(idx, ele){
                        var rules = {},
                            $element = $( ele ),
                            dataSet = $element.data(),
                            type = ele.getAttribute( "type" ),
                            method, value, msg;

                        for ( method in $.validator.methods ) {
                            let method_name = method.charAt( 0 ).toUpperCase() + method.substring( 1 ).toLowerCase();
                            value = $element.data( "rule" +  method_name );
                            msg = $element.data( "msg" + method_name );
                            // Cast empty attributes like `data-rule-requered` to `true`
                            if ( dataSet.hasOwnProperty("rule" +  method_name) ) {
                                if( !validationRules.rules.hasOwnProperty(ele.id) ) {
                                    validationRules.rules[ele.id] = [];
                                    validationRules.messages[ele.id] = [];
                                }
                                validationRules.rules[ele.id][method] = value;
                                validationRules.messages[ele.id][method] = msg || MESSAGES_VALIDATORS[method];
                            }
                        }
                    });
                    var validator = $form.data('validator');
                    if( validator ) {
                        $form
                            .off( ".validate" )
                            .removeData( "validator" )
                            .find( ".validate-equalTo-blur" )
                            .off( ".validate-equalTo" )
                            .removeClass( "validate-equalTo-blur" );
                    }
                    $form.validate(validationRules);
                },
                formSubmit ($form, validator, ev) {
                    const _self = this;
                    let options = {
                        dataType: 'json',
                        beforeSend: function () {
                            $('body').block(REP_IMAGE_LOADING);
                        },
                        success: function(response, status, xhr){
                            let {edit_post_url, id} = response.data;
                            $('.rep-builder-layout').removeClass('d-none');
                            $('.rep-builder-layout .rep-drag-drop-layout').attr('href', edit_post_url);
                            $('input[name="id"]', $form).val(id);
                            _self.render();
                            _self.trigger('pushNotification', response.data.message, 'success');
                        },
                        error: function(xhr, status, errThrow){
                            let textError = self.getTextMessageByXHR(xhr, errThrow);
                            _self.trigger('pushNotification', textError, 'error');
                            return;
                        },
                        complete: function(xhr, status){
                            $('body').unblock(REP_IMAGE_LOADING);
                            return;
                        }
                    };
                    self.xhr_abort = $form.ajaxSubmit(options);
                },
                templateParse (type, data) {
                    return this.template.hasOwnProperty(type) ? this.template[type](data) : '';
                },

                eventUploadFile(ev) {
                    const _self = this;
                    let $this = $(ev.currentTarget), files = ev.currentTarget.files;
                    let _validFileExtensions = ["application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"];
                    let blnValid = false;
                    let $progress = $('.file-cv-progress-bar'), $file_name = $('.file-cv-name');
                    $file_name.addClass('d-none');
                    //$progress.removeClass('d-none');
                    $.each(files, function (index, file) {
                        let fileType = file.type;
                        if( !_validFileExtensions.includes(fileType) ){
                            blnValid = true;
                            return;
                        }
                        let size = file.size, size_percent = size / 100;
                        /*for (let i = 0; i <= 10000; i++){
                            $progress.find('.progress-bar').attr('aria-valuenow', i).css('width', i * 100 / 10000 + '%');
                            if( i === 10000 ){
                                $progress.addClass('d-none');
                                $file_name.removeClass('d-none');
                                $file_name.text(file.name);
                            }
                        }*/
                        $file_name.removeClass('d-none');
                        $file_name.text(file.name);
                    });
                    if( blnValid ){
                        _self.trigger('pushNotification', 'File invalid image', 'error')
                        return;
                    }
                },

                eventRepeaterAdd(ev) {
                    const _self = this;
                    ev.preventDefault();
                    let $this = $(ev.currentTarget), {type, rowspan} = $this.data(), $parent = $this.closest('tr');
                    let idx = (new Date()).getTime(), html = '';
                    html = this.templateParse(type, {idx});
                    if( type === 'elv' ){
                        rowspan += 5;
                    }else{
                        rowspan += 6;
                    }
                    $this.data('rowspan', rowspan).attr('data-rowspan', rowspan);
                    $('.' + type + '-main').attr('rowspan', rowspan);
                    $(html).insertBefore($parent);
                },
                eventRepeaterRemove(ev) {
                    ev.preventDefault();
                    let $this = $(ev.currentTarget), {type, target} = $this.data();
                    let title = type === 'elv' ? 'học vấn' : 'kinh nghiệm làm việc';
                    self.createModal({
                        id: 'modal-confirm-delete-element',
                        title: 'Bạn có chắc chắn xoá ' + title + '?',
                        yesButtonText: 'Có',
                        noButtonText: 'Không',
                        funcYes: () => {
                            let $action_add = $('.btn-apply-action-add[data-type="' + type + '"]'), {rowspan} = $action_add.data();
                            if( type === 'elv' ){
                                rowspan -= 5;
                            }else{
                                rowspan -= 6;
                            }
                            $action_add.data('rowspan', rowspan).attr('data-rowspan', rowspan);
                            $('.' + type + '-main').attr('rowspan', rowspan);
                            $(target).remove();
                            $('#modal-confirm-delete-element').modal('hide');
                        }
                    });
                },
            });
            this.rep_apply_cv = new _rep_apply_cv();
            this.rep_apply_cv.on('pushNotification', function(message, type) {
                toastr.options = self.getToastrOption();
                toastr[type](message, '');
            });
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
                $dialogConfirm.attr('style', 'z-index: 2060 !important');
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
        createModal (args) {
            let defaults = {
                id: 'createModal',
                container: 'body',
                title: '',
                body: true,
                bodyHtml: '',
                modalClass: '',
                modalContentClass: '',
                yesButtonClass: '',
                noButtonClass: '',
                header: true,
                footer: true,
                yesButton: true,
                yesButtonText: 'Yes',
                noButton: true,
                noButtonText:  'No',
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
                    '<h4 class="modal-title" data-title="' + data.title.replaceAll('"', '&#34;') + '">' + data.title + '</h4>\n' +
                    '</div>\n';
            }
            if( data.body ) {
                body = '<div class="modal-body">' + data.bodyHtml + '</div>';
            }
            if( data.footer ) {
                let yesButton = '', noButton = '', additionalHtml = '';
                if( data.yesButton ){
                    yesButton = '<button type="button" class="btn btn-default btn-primary btn-yes font-weight-500 modal-btn-action ' + data.yesButtonClass + '">' + data.yesButtonText + '</button>\n';
                }
                if( data.noButton ){
                    noButton = '<button type="button" data-dismiss="modal" class="btn btn-default btn-close font-weight-500 btn-no ' + data.noButtonClass + '">' + data.noButtonText + '</button>';
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
                '    <div class="modal-dialog modal-close-inside modal-dialog-centered ' + data.modalClass + '">\n' +
                '        <div class="modal-content ' + data.modalContentClass + '">\n'
                + header + body + footer +
                '        </div>\n' +
                '    </div>\n' +
                '</div>';
            let $container = $(data.container);
            if( !$container.find(modalID).length ){
                if($container.find('.modal-backdrop').length){
                    $(modal).insertBefore($('body').find('.modal-backdrop')[0]);
                }else{
                    $container.append(modal);
                }
            }else{
                $container.find(modalID).html($(modal).html());
            }

            this.bindEventDialogConfirm(modalID, {yes: data.funcYes, no: data.funcNo}, data.args);

            $(modalID).on('hidden.bs.modal.confirm', function (){
                $(this).remove();
            });
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
        settingUnderScore () {
            _.templateSettings = {
                evaluate: /<#([\s\S]+?)#>/g,
                interpolate: /{{{([\s\S]+?)}}}/g,
                escape: /{{([^}]+?)}}(?!})/g
            };
        },
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
        getTextMessageByXHR (xhr, errThrow){
            let textError = '';
            if( xhr.hasOwnProperty('responseJSON') && xhr.responseJSON.hasOwnProperty( 'message' ) && (xhr.responseJSON.message != '') ){
                textError = xhr.responseJSON.message;
            }else if( errThrow != '' ){
                textError = xhr.getResponseHeader('xhr-message') || errThrow;
                try{
                    textError = JSON.parse(textError);
                } catch(e){
                    textError = boardTrans._t('An error occurred, please try again');
                }
            }else{
                textError = boardTrans._t('An error occurred, please try again');
            }
            return textError;
        },
    }
    $.REP_APPLY_CV.init();
});