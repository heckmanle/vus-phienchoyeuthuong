jQuery(function ($){
    let Events;
    Events =  {
        init: function (){
            this.$form = $('#add-category');
            this.IMAGE_LOADING = {
                message: '<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
                css: {border: '', padding: 'none', width: '40px', height: '40px'}

            };
            _.templateSettings = {
                evaluate: /<#([\s\S]+?)#>/g,
                interpolate: /{{{([\s\S]+?)}}}/g,
                escape: /{{([^}]+?)}}(?!})/g
            };
            this.default_data_form = {
                id: "",
                client_type: '',
                client_id: '',
                name: '',
                phone: '',
                email: '',
                avatar: '',
                note: '',
                address: '',
                website: '',
                tax_code: '',
                billing_address: '',
                branch: '',
                contact_person_information: '',
                contact_person_informations: '',
            };
            this.file = [];
            this.$row_from = _.template($('script#tpl_add_row_clients_form').html());
            this.$modalForm = $('#exampleModalLong');
            this.initEvent();
            this.initFrom();
            this.initModal();
            //this.eventExternal();
        },
        initSelect2(){
            $('.select2-client').select2({
                width: '100%',
                dropdownParent: $('#frm-handle-clients'),
            });
        },
        initRepeater(){
            const self = this;
            var repeaterAccount = $(".account-client-repeater").repeater({
               /* repeaters: [{
                    selector: '.repeater-progress'
                }]*/
            });

            $(document)
                .off('click', '.btn_add_more_users')
                .on('click', '.btn_add_more_users', function(ev){
                    repeaterAccount.addItem();
                    self.initSelect2();
                });
        },
        initDatepicker(){
            const self = this;
            $("input#datepicker-client", '#frm-handle-clients').datepicker({
                dateFormat : "dd/mm/yy",
            });
        },
        /*eventExternal () {
            const self = this;
            $(document)
                .off('click', '.btn_add_more_users')
                .on('click', '.btn_add_more_users', function (ev) {
                    ev.preventDefault();
                    let indexGroup = $('#indexGroup').val();
                    indexGroup = parseInt(indexGroup) + 1;
                    $('#htmlTemplateNull .personal_group').attr('id', 'personal_group-'+indexGroup);
                    $('#indexGroup').val(indexGroup);

                    let html = $('#htmlTemplateNull').html();
                    $('#memberslist').append(html);

                });
        },*/
        initModal(){
            const self = this;
            $(document).off('show.bs.modal', '#exampleModalLong')
                .on('show.bs.modal', '#exampleModalLong', function (ev) {
                    let target = $(ev.relatedTarget);
                    $('#frm-handle-clients', $(this)).html('');
                    $('#fileUpload').val('');
                    if(target.hasClass('edit-client')){
                        let {send} = target.data();
                        self.randerClient(send, $(this));
                    }else{
                        let row = self.$row_from(self.default_data_form);
                        $('#frm-handle-clients', $(this)).append(row);
                        self.initRepeater();
                        self.initSelect2();
                    }
                });
        },
        randerClient(send, form){
            const self = this;
            let options = {
                url: AJAX_URL,
                type: 'post',
                data: send,
                dataType: 'json',
                beforeSend: function () {
                    $('body').block(self.IMAGE_LOADING);
                },
                success: function(response, status, xhr){
                    if(response){
                        let {id, client_type, client_id, name, phone, email, address, avatar, tax_code, billing_address, branch, contact_person_information, website, note, contact_person_informations} = response;
                        let data_form = self.$row_from({
                            id: id,
                            client_type: client_type,
                            client_id: client_id,
                            name: name,
                            phone: phone,
                            note: note,
                            avatar: avatar,
                            email: email,
                            address: address,
                            website: website,
                            tax_code: tax_code,
                            billing_address: billing_address,
                            branch: branch,
                            contact_person_information: contact_person_information,
                            contact_person_informations: contact_person_informations,
                        });
                        $('#frm-handle-clients', form).append(data_form);
                        self.initDatepicker();
                        self.initRepeater();
                        self.initSelect2();
                    }
                    return;
                },
                error: function(xhr, status, errThrow){
                    return;
                },
                complete: function(xhr, status){
                    $('body').unblock({fadeOut: 0});
                    return;
                }
            };
            $.ajax(options);
        },
        initFrom(){
            const self = this;
            $('#frm-handle-clients').submit(function(ev) {
                ev.preventDefault();
                const $this = $(this);
                //let serialize = $this.serialize();
                let $form = $('#frm-handle-clients');
                var formData = new FormData($form[0]);
                let options = {
                    url: AJAX_URL,
                    type: 'post',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('body').block(self.IMAGE_LOADING);
                    },
                    success: function(response, status, xhr){
                        if(status == 'success'){
                            location.reload();
                        }
                        return;
                    },
                    error: function(xhr, status, errThrow){
                        var textError = '';
                        if (xhr.hasOwnProperty('responseJSON') && xhr.responseJSON.hasOwnProperty('message') && xhr.responseJSON.message != '') {
                            textError = xhr.responseJSON.message;
                        } else if (errThrow != '') {
                            textError = xhr.getResponseHeader('xhr-message') || errThrow;
                            try {
                                textError = JSON.parse(textError);
                            } catch (e) {
                            }
                        }
                        toastr.error(textError);
                        return;
                    },
                    complete: function(xhr, status){
                        $('body').unblock(self.IMAGE_LOADING);
                        return;
                    }
                };
                $.ajax(options);
            });

            $('#frm-import-clients').submit(function(ev) {
                ev.preventDefault();
                const $this = $(this);
                var formData = new FormData($this[0]);
                formData.append('file', self.file);
                let options = {
                    url: AJAX_URL,
                    type: 'post',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('body').block(self.IMAGE_LOADING);
                    },
                    success: function(response, status, xhr){
                        if(status == 'success'){
                            location.reload();
                        }
                        return;
                    },
                    error: function(xhr, status, errThrow){
                        return;
                    },
                    complete: function(xhr, status){
                        $('body').unblock(self.IMAGE_LOADING);
                        return;
                    }
                };
                $.ajax(options);
            });
        },
        initEvent(){
            const self = this;
            $(document).off('select2:select', '.select-user')
                .on('select2:select', '.select-user', function (ev){
                    let target = $(ev.currentTarget);
                    let user_id = $(this).val();
                    let $form_row = target.closest('.form-row');
                    if(user_id) {
                        let send = {
                            action: 'handle_ajax',
                            func: 'get_user',
                            user_id: user_id,
                        };
                        let options = {
                            url: AJAX_URL,
                            type: 'post',
                            data: send,
                            dataType: 'json',
                            beforeSend: function () {
                                $('body').block(self.IMAGE_LOADING);
                            },
                            success: function (response, status, xhr) {
                                if (response.data) {
                                    let {user} = response.data;
                                    if(user) {
                                        if (target.hasClass('select-user-by-name')) {
                                            $('.select-user-by-phone', $form_row).val(user.id).trigger('change');
                                        } else {
                                            $('.select-user-by-name', $form_row).val(user.id).trigger('change');
                                        }
                                        $('input.email-review', $form_row).val(user.email);
                                    }
                                }
                                return;
                            },
                            error: function (xhr, status, errThrow) {
                                return;
                            },
                            complete: function (xhr, status) {
                                $('body').unblock(self.IMAGE_LOADING);
                                return;
                            }
                        };
                        $.ajax(options);
                    }else{
                        if (target.hasClass('select-user-by-name')) {
                            $('.select-user-by-phone', $form_row).val("").trigger('change');
                        } else {
                            $('.select-user-by-name', $form_row).val("").trigger('change');
                        }
                        $('input.email-review', $form_row).val("");
                    }
                });


            $(document).off('change', 'input[name=import]').on('change', 'input[name=import]', function (ev){
                let files = this.files;
                var validMSTypes = [
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',];
                if (validMSTypes.includes(files[0]['type'])) {
                    self.file = files[0];
                }else{

                }
                return;
            });

            $(document).off('change', '.checkall')
                .on('change', '.checkall', function (ev){
                    let $this = $(ev.currentTarget);
                    let checked = $this.prop('checked');
                    $('.check-item').prop('checked', checked);
                });
            $(document).off('click', '.btn-delete-clients')
                .on('click', '.btn-delete-clients', function (ev){
                    ev.preventDefault();
                    let atLeastOneIsChecked = $('input[name="clients[]"]:checked');
                    let clients = [];
                    if(atLeastOneIsChecked.length > 0){
                        $.each(atLeastOneIsChecked, function (key, value){
                            clients.push($(value).val());
                        });
                        if(clients.length > 0){
                            let send = {
                                action: 'handle_ajax',
                                func: 'delete_clients',
                                clients: clients
                            }
                            let args_modal = {
                                id: 'modal-delete-clients-confirm',
                                title: 'Bạn có chắc muốn xóa khách hàng ?',
                                bodyHtml: 'Bảo đảm không có hoạt động nào đang còn hiệu lực với khách hàng muốn xóa.',
                                body: true,
                                noButtonText: 'Không',
                                funcYes: function () {
                                    let options = {
                                        url: AJAX_URL,
                                        type: 'post',
                                        data: send,
                                        dataType: 'json',

                                        beforeSend: function () {
                                            $('body').block(self.IMAGE_LOADING);
                                        },
                                        success: function(response, status, xhr){
                                            if(response){
                                                let {data_false, data_success} = response.data;
                                                if(data_false.length > 0) {
                                                    $.each(data_false, function (key, item) {
                                                        //toastr.error("");
                                                    })
                                                }
                                                if(data_success.length > 0){
                                                    $.each(data_success, function (key, item) {
                                                        let row = $('.client-' + item);
                                                        if(row){
                                                            row.css("background-color", "red");
                                                            setTimeout(function (ev){
                                                                row.remove();
                                                            },400)
                                                        }
                                                    })
                                                }
                                                $('#modal-delete-clients-confirm').modal('hide');
                                            }
                                            return;
                                        },
                                        error: function(xhr, status, errThrow){
                                            return;
                                        },
                                        complete: function(xhr, status){
                                            $('body').unblock(self.IMAGE_LOADING);
                                            return;
                                        }
                                    };
                                    $.ajax(options);
                                },
                                args: []
                            };
                            self.createModal(args_modal);
                        }
                    }
                })

            $(document).off('change', '#fileUpload')
                .on('change', '#fileUpload', function (ev){
                    const file = ev.target.files[0];
                    var validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
                    var fileType= ev.target.files[0]['type']
                    if (validImageTypes.includes(fileType)) {
                        const result = self.getBase64(file);
                        result.then(function(dataimage){
                            var div=$('.dropzone1');
                            var bar = div.removeClass('.frame-default');
                            bar.html('<img id="img" class="img-upload" src="'+ dataimage +'"><input type="hidden" name="avatar" value="'+ dataimage +'">');
                        });
                    }
                    else{
                        var div=$('.dropzone1');
                        var bar = div.removeClass('.dz-default');
                        bar.html('<span style="color: red">Chỉ nhận file hình ảnh .png .jpg .jpeg</span>');
                    }
                });
        },
        getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
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
                yesButtonText: 'Có',
                noButton: true,
                noButtonText:  'Không',
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
                '    <div class="modal-dialog modal-sm ' + data.modalClass + '">\n' +
                '        <div class="modal-content">\n'
                + header + body + footer +
                '        </div>\n' +
                '    </div>\n' +
                '</div>';
            if( !$('body').find(modalID).length ){
                $('body').append(modal);
            }

            //Applications.hooks.doAction('app/createModal/' + modalID, $('body').find(modalID), data)

            this.bindEventDialogConfirm(modalID, {yes: data.funcYes, no: data.funcNo}, data.args);
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
            $dialogConfirm.modal('show');
            return this;
        },

    }
    $(".select2-basic").select2({
        dropdownParent: $("#tpl_add_row_clients_form")
    });
    Events.init();
});


