
jQuery(function ($){
    let ImportBookingservices;
    ImportBookingservices =  {
        init: function () {
            this.IMAGE_LOADING = {
                message: '<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
                css: {border: '', padding: 'none', width: '40px', height: '40px'}

            };
            _.templateSettings = {
                evaluate: /<#([\s\S]+?)#>/g,
                interpolate: /{{{([\s\S]+?)}}}/g,
                escape: /{{([^}]+?)}}(?!})/g
            };
            this.$row_import_booking = _.template($('script#tpl_add_row_import_booking').html());
            this.data_product = '';
            this.modal_import_booking = $('#exampleModalLong');
            this.initEvent();
            this.initModalAddFile();
            this.initAddFile();
            this.initForm();
        },
        initEvent(){
            const self = this;
          $(document).off('click', '.btn-confirm-add-booking')
              .on('click', '.btn-confirm-add-booking', function (ev) {
                  ev.preventDefault();
                  $('#frm-add-booking').trigger('submit');
              });
        },
        initForm(){
            const self = this;
            $(document).off('submit', '#frm-add-booking')
                .on('submit', '#frm-add-booking', function (ev){
                    ev.preventDefault();
                    const $this = $(this);
                    let formData = new FormData($this[0]);
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
                            if(response.data){
                                //$('#exampleModalLong').modal('hide');
                                let {message} = response.data;
                                toastr.success(message);
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
        },
        initModalAddFile(){
            const self = this;
            /*$(document).off('shown.bs.modal', '#exampleModalLong')
                .on('shown.bs.modal', '#exampleModalLong', function (){
                    if ($(".modal-backdrop").length > 1) {
                        $(".modal-backdrop").not(':first').remove();
                    }
                });*/
            $(document).off('show.bs.modal', '#exampleModalLong')
                .on('show.bs.modal', '#exampleModalLong', function (ev){
                    let target = $(ev.relatedTarget);
                    let {send} = target.data();
                    let row = self.$row_import_booking({event_id: send.id});
                    $('.modal-body', self.modal_import_booking).append(row);
                    if(target.hasClass('add_new_booking_main')){
                        self.initForm();
                    }else if(target.hasClass('preview_booking_main')){
                        self.renderTablePreviewBooking(send.id);
                    }
                });
            $(document).off('hidden.bs.modal', '#exampleModalLong')
                .on('hidden.bs.modal', '#exampleModalLong', function (){
                    $('.modal-body', $(this)).html('');
                    self.data_product = [];
                });
        },
        renderTablePreviewBooking(event_id){
            const self = this;
            if(event_id) {
                let send = {
                    action: 'handle_ajax',
                    func: 'get_table_preview_booking',
                    event_id: event_id,
                }
                let options = {
                    url: AJAX_URL,
                    type: 'post',
                    data: send,
                    dataType: 'json',
                    beforeSend: function () {
                        $('body').block(IMAGE_LOADING);
                    },
                    success: function (response, status, xhr) {
                        if(response.data){
                            let {table} =response.data;

                            $('.table-preview-booking', self.modal_import_booking).html(table);

                            $('.datalist', self.modal_import_booking).addClass('preview_remove_btn');
                        }
                        return;
                    },
                    error: function (xhr, status, errThrow) {
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
                    complete: function (xhr, status) {
                        $('body').unblock({fadeOut: 0});
                        return;
                    }
                };
                $.ajax(options);
            }
        },
        initAddFile(){
            const self = this;
            $(document).off('change', 'input[name=file_import]').on('change', 'input[name=file_import]', function (ev){
                let files = this.files;
                var validMSTypes = [
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',];
                if (validMSTypes.includes(files[0]['type'])) {
                    self.loadDataFile( files[0] );
                }else{

                    // show message invalid type file

                }
                return;
            });
        },
        loadDataFile(file){
            const self = this;
            let event_id = $('input[name=event_id]', self.modal_import_booking).val();
            var formData = new FormData();
            formData.append("file_import", file);
            formData.append("event_id", event_id);
            formData.append('func', 'import_file_booking');
            formData.append('action', 'handle_ajax');
            //formData.append('_wp_nonce', INVENTORY.nonce_import_file);
            if(event_id) {
                $.ajax({
                    url: AJAX_URL,
                    data: formData,
                    type: 'post',
                    processData: false,
                    contentType: false,
                    beforeSend: function (xhr) {
                        $('body').block(self.IMAGE_LOADING);
                    },
                    success: function (response) {
                        if(response.data) {
                            $('.table-preview-booking', self.modal_import_booking).html('');
                            let {table, event_id} = response.data;
                            if (table && event_id) {
                                $('.table-preview-booking', self.modal_import_booking).append(table);
                                let handle_booking = $('tr.event-' + event_id).find('.btn-handle-booking');
                                handle_booking.removeClass('add_new_booking_main').addClass('preview_booking_main');
                            } else {
                                $('.table-preview-booking', self.modal_import_booking).append('<span>Không tìm thấy dữ liệu</span>');
                            }
                        }
                        return;
                    },
                    error: function (xhr, status, errThrow) {
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
                    complete: function (xhr, status) {
                        $('body').unblock(self.IMAGE_LOADING);
                        return;
                    }
                });
            }
        },

    }
    ImportBookingservices.init();
});