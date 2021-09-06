
jQuery(function ($){
    Applications.helpers.settingUnderScore();
    $.bookingServices =  {
        init: function () {
            this.$el = $('.wrapper-booking-services');
            this.$templateForm = $('script#tpl-form-content');
            this.$templatePrint = $('script#tpl-print-booking');
            this.$templateProductItem = $('script#tpl-repeater-product-item');
            this.$modalForm = $('#modal-booking-service');
            this.backbone();
            this.initForm();
            this.initModal();
            this.eventExternal();
            this.checkPopupOpenLink();


        },
        backbone () {
            const self = this;
            const _vat = $('#_vat').val();
            const _discount = $('#_discount').val();
            let bk = Backbone.View.extend({
                el: self.$el,
                template: {
                    form: _.template(self.$templateForm.html()),
                    product_item: _.template(self.$templateProductItem.html()),
                    print: _.template(self.$templatePrint.html()),
                },
                option_toastr: Applications.helpers.getToastrOption(),
                dataTable: {},
                data_defaults: {
                    id: '',
                    disabled: '',
                    status: '',
                    client_type: '',
                    per_charge: 0,
                    image_confirm: '',
                    date: moment().format('DD/MM/YYYY'),
                    booth: '',
                    votes: '',
                    discount: _discount,
                    vat: _vat,
                    events: {},
                    payment_type: 'CK',
                    author: BOOKING.user,
                    client: {
                        address: '',
                        contact_person_informations: {
                            name: '',
                            phone: '',
                        },
                        contact_person_information: {
                            name: '',
                            phone: '',
                        },
                    },
                    list_of_equipment: [
                        {
                            product: {
                                product_code: '--',
                            },
                            into_money: '',
                            quantity: 1,
                        }
                    ],
                    idx: 0,
                },
                events: {
                    'click .js-action-delete': 'eventDelete',
                    'change #table-booking .check-all': 'eventCheckAll',
                    'change #table-booking .check-row': 'eventCheckRow',
                },
                initialize () {
                    const _self = this;
                    if( $('#table-booking').length ) {
                        setTimeout(function () {
                            _self.dataTable = window._jq("#table-booking").DataTable({responsive: !0});
                        }, 250);
                    }
                },

                templateParse (key, data) {
                    return this.template.hasOwnProperty(key) ? this.template[key](data) : '';
                },

                async loadData (send) {
                    let _response = {};
                    let options = {
                        url: AJAX_URL,
                        type: 'get',
                        data: send,
                        funcSuccess: function (response, status, xhr) {
                            _response = response.data;
                            return;
                        },
                        funcError: function (xhr, status, errThrow) {
                            return;
                        },
                    }
                    await Applications.helpers.actionAjax(options);
                    return _response;
                },

                addEventProduct($ele){
                    let products = BOOKING.products.map(function(item){
                        let it = Object.assign({}, item);
                        it.text = it.product_title;
                        return it;
                    });
                    $('.select2-product-table', $ele).select2({
                        data: products,
                        dropdownParent: self.$modalForm,
                    });
                    $('.select2-product-table', $ele).on('select2:select', function (ev) {
                        let data = ev.params.data, $parent = $(this).closest('tr');
                        if( !data.hasOwnProperty('product_code') ){
                            data.product_code = '--';
                        }
                        let quantity = $('.input-product-quantity', $parent).val();
                        $parent.find('.td-product-unit').text(data.product_unit);
                        $parent.find('.td-product-code').text(data.product_code);
                        $parent.find('.td-product-price').text(Applications.helpers.convertStringToMoney(data.product_pay));
                        $parent.find('.td-product-price').data('value', data.product_pay);
                        $parent.find('.td-total').text(Applications.helpers.convertStringToMoney(data.product_pay * quantity));
                        $('.input-product-quantity', $parent).trigger('change');
                    });
                },

                addEvent($ele){
                    let $form = self.$modalForm.find('form');
                    let clients_id = BOOKING.clients.map(function(item){
                        let it = Object.assign({}, item);
                        it.text = it.client_id;
                        return it;
                    });
                    let clients_name = BOOKING.clients.map(function(item){
                        let it = Object.assign({}, item);
                        it.text = it.name;
                        return it;
                    });
                    let events = BOOKING.events.map(function(item){
                        let it = Object.assign({}, item);
                        it.text = it.title;
                        return it;
                    });

                    $('.select-events', $ele).select2({
                        data: events,
                        dropdownParent: self.$modalForm,
                    });

                    $('.select-client.select-client-id', $ele).select2({
                        data: clients_id,
                        dropdownParent: self.$modalForm,
                    });
                    $('.select-client.select-client-name', $ele).select2({
                        data: clients_name,
                        dropdownParent: self.$modalForm,
                    });

                    $("#datepicker-bookingservices", $ele).datepicker({
                        dateFormat : "dd/mm/yy",
                    });

                    let $form_select_location_event = $('#form-select-location-event', $ele);
                    $form_select_location_event.select2();
                    $('.select-events', $ele).on('select2:select', function (ev) {
                        let data = ev.params.data;
                        let optionDefault = new Option('-Chọn gian hàng-', '', false, false);
                        $form_select_location_event.find('option').remove();
                        $form_select_location_event.append(optionDefault).trigger('change');
                        Object.values(data.location_diagram.area).forEach(item => {
                            let newOption = new Option(item.location, item.location, false, false);
                            $form_select_location_event.append(newOption).trigger('change');
                        });
                    })
                    const callbackChangeClient = (data) => {
                        let client_type = BOOKING.client_type.hasOwnProperty(data.client_type) ? BOOKING.client_type[data.client_type] : '';
                        $('#form-client-type').val(client_type);
                        $('#author-name').val('');
                        $('#author-phone').val('');
                        if(data.contact_person_information){
                            let contact_person_information = data.contact_person_information;
                            $('#author-name').val(contact_person_information.name);
                            $('#author-phone').val(contact_person_information.phone);
                        }
                    }
                    $('.select-client.select-client-id', $ele).on('select2:select', function (ev) {
                        let data = ev.params.data;
                        $('.select-client.select-client-name', $ele).val(data.id).trigger('change');
                        callbackChangeClient(data);
                    })
                    $('.select-client.select-client-name', $ele).on('select2:select', function (ev) {
                        let data = ev.params.data;
                        $('.select-client.select-client-id', $ele).val(data.id).trigger('change');
                        callbackChangeClient(data);
                    })
                    this.addEventProduct($ele);
                },

                eventCheckAll (ev) {
                    let $this = $(ev.currentTarget);
                    let $table = $this.closest('table');
                    let checked = $this.prop('checked');
                    if(checked)
                        $('.js-action-delete').removeClass('d-none');
                    else
                        $('.js-action-delete').addClass('d-none');
                    $table.find('.check-row').prop('checked', checked);
                },
                eventCheckRow (ev) {
                    let $this = $(ev.currentTarget);
                    let $table = $this.closest('table');
                    let check_length = $table.find('.check-row').length;
                    let numberOfChecked = $table.find('.check-row:checked').length;
                    let prop;
                    if( check_length !== numberOfChecked ){
                        prop = false;
                    }else{
                        prop = true;
                    }
                    if( numberOfChecked > 0 ){
                        $('.js-action-delete').removeClass('d-none');
                    }else{
                        $('.js-action-delete').addClass('d-none');
                    }
                    $table.find('.check-all').prop('checked', prop);
                },
                eventDelete (ev) {
                    const _self = this;
                    let $this = $(ev.currentTarget);
                    let {table, send} = $this.data();
                    let $checked = $(table).find('.check-row:checked'), id = [];
                    $checked.each(function(){
                        id.push($(this).val());
                    });
                    let args_modal = {
                        id: 'modal-bks-confirm',
                        title: 'Bạn có chắc xoá phiếu đăng ký được chọn?',
                        body: true,
                        bodyHtml: '',
                        funcYes: function () {
                            let options = {
                                data: Object.assign({}, send, {id: id}),
                                method: 'POST',
                                beforeSend: function(xhr, settings) {
                                    $('#modal-bks-confirm .modal-body').html('');
                                },
                                funcSuccess: function(response, status, xhr){
                                    let {list_data} = response.data;
                                    $('#modal-bks-confirm').modal('hide');
                                    $('.js-action-delete').addClass('d-none');
                                    toastr.options = _self.option_toastr;
                                    toastr['success'](response.data.message, '');
                                    _self.dataTable.clear().draw();
                                    _self.dataTable.rows.add(list_data);
                                    _self.dataTable.columns.adjust().draw();
                                },
                                funcError: function(xhr, status, errThrow){
                                    let textError = Applications.helpers.getTextMessageByXHR(xhr, errThrow);
                                    textError = Applications.helpers.renderAlert(textError);
                                    $('#modal-bks-confirm .modal-body').html(textError);
                                }
                            }
                            Applications.helpers.actionAjax(options);
                        },
                        args: []
                    };
                    Applications.helpers.createModal(args_modal);
                },
            });
            this.bookingService = new bk();

            this.bookingService.on('pushNotification', function(message, type) {
                toastr.options = this.option_toastr;
                toastr[type](message, '');
            });
        },
        initForm () {
            const self = this;
            $('form.frm-validation', self.$modalForm).each(function(){
                self.formValidate($(this));
            });
        },
        checkPopupOpenLink() {
            //
        },
        formValidate ($form) {
            const _self = this;
            var $validation = $form.find('[data-is-validation="true"]');
            var validationRules = {
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
            };
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
                    // Cast empty attributes like `data-rule-required` to `true`
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
            let options = {
                dataType: 'json',
                beforeSubmit: function(serialize, form, option) {
                    $form.find('.message-notification').html('');
                },
                beforeSend: function () {
                    $('body').block(IMAGE_LOADING);
                },
                success: function(response, status, xhr){
                    window.onbeforeunload = null;
                    if( response.data.hasOwnProperty('_wp_http_referer') && response.data._wp_http_referer != '' ){
                        window.location.href = response.data._wp_http_referer;
                    }else{
                        window.location.reload();
                    }
                    return;
                },
                error: function(xhr, status, errThrow){
                    let textError = Applications.helpers.getTextMessageByXHR(xhr, errThrow);
                    $form.find('.message-notification').html(Applications.helpers.renderAlert(textError));
                    return;
                },
                complete: function(xhr, status){
                    $('body').unblock(IMAGE_LOADING);
                    return;
                }
            };
            self.xhr_abort = $form.ajaxSubmit(options);
        },
        eventExternal () {
            const self = this;
            $(document)
                .off('click', '.add-more-product')
                .on('click', '.add-more-product', function (ev) {
                    ev.preventDefault();
                    let data = Object.assign({},  self.bookingService.data_defaults.list_of_equipment[0]);
                    data.parent_id = $('#booking-id').length ? $('#booking-id').val() : '';
                    data.idx = 0;
                    let html = self.bookingService.templateParse('product_item', data);
                    let $html = $(html);
                    self.bookingService.addEventProduct($html);
                    $('#table-product-bookingservice tbody').append($html);
                });
            $(document)
                .off('change', '.bk-image')
                .on('change', '.bk-image', function (ev) {
                    let $this = $(this);
                    let $parent = $this.closest('.form-group-image-upload'), $label = $this.closest('.lbl-bk-image');
                    if( this.files && this.files.length > 0 ){
                        let file = this.files[0];
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('.view-image', $parent).attr('src', e.target.result);
                            $parent.find('.view-image-content').removeClass('d-none')
                            $label.addClass('d-none');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            $(document)
                .off('click', '.remove-image')
                .on('click', '.remove-image', function(ev){
                    ev.preventDefault();
                    let $this = $(this);
                    let $td = $this.closest('.form-group-image-upload'), $parent = $this.closest('.view-image-content');
                    $parent.addClass('d-none');
                    $parent.find('img').attr('src', '');
                    $td.find('.lbl-bk-image').removeClass('d-none');

                });
            $(document)
                .off('click', '.product-row-remove')
                .on('click', '.product-row-remove', function (ev) {
                    ev.preventDefault();
                    let $this = $(this);
                    let args_modal = {
                        id: 'modal-pro-form-confirm',
                        title: 'Bạn có chắc xoá dòng này?',
                        body: true,
                        noButtonText:  'Huỷ',
                        bodyHtml: '',
                        funcYes: function () {
                            $('#modal-pro-form-confirm').modal('hide');
                            $this.closest('tr').remove();
                        },
                        args: []
                    };
                    Applications.helpers.createModal(args_modal);
                });
            $(document)
                .off('change', '.input-product-quantity')
                .on('change', '.input-product-quantity', function (ev) {
                    let $this = $(this), $table_product_bookingservice = $this.closest('#table-product-bookingservice');
                    let $tbody = $table_product_bookingservice.find('tbody'), $parent = $this.closest('tr');
                    let total = 0;
                    $tbody.find('tr').each(function () {
                        let quantity = $('.input-product-quantity', $(this)).val();
                        let $price = $('.td-product-price', $(this)), {value} = $price.data();
                        total += quantity * value;
                    });
                    let {value: price} = $('.td-product-price', $parent).data();
                    $parent.find('.td-total').text(Applications.helpers.convertStringToMoney(price * $this.val()));
                    total = total + ($('#form-vat').val() * total / 100)  - ($('#form-discount').val() * total / 100);
                    $('#form-total-money').val(Applications.helpers.convertStringToMoney(total));
                });
            $(document)
                .off('change', '#form-discount, #form-vat')
                .on('change', '#form-discount, #form-vat', function (ev) {
                    let $table_product_bookingservice = $('#table-product-bookingservice'),
                        $tbody = $table_product_bookingservice.find('tbody'),
                        $tr = $tbody.find('tr:first-child');
                    $('.input-product-quantity', $tr).trigger('change');
                })
        },
        initModal () {
            const self = this;
            self.$modalForm.on('show.bs.modal', function(ev){
                let $this = $(this), $relatedTarget = $(ev.relatedTarget);

                let p = new Promise(async (resolve, reject) => {
                    if( $relatedTarget.hasClass('get-once') ){
                        let {send} = $relatedTarget.data();
                        let res = await self.bookingService.loadData(send);
                        res.client_type = BOOKING.client_type.hasOwnProperty(res.client_type) ? BOOKING.client_type[res.client_type] : '';
                        res.disabled = 'disabled';
                        resolve(res);
                    }else{
                        resolve(self.bookingService.data_defaults);
                    }
                });
                p.then(function(data) {
                    let html, $html;
                    // if( data.hasOwnProperty('status') && data.status === 'done' ){
                    //     html = self.bookingService.templateParse('print', data);
                    //     $html = $(html);
                    // }else {

                        if(data.client.contact_person_information == null){
                            data.client.contact_person_information = {
                                name: '',
                                phone: '',
                            }
                        }
                        html = self.bookingService.templateParse('form', data);
                        $html = $(html);
                        console.log(BOOKING.users);
                        if( BOOKING.users.length && data.hasOwnProperty('status') && (data.status === 'pending-draft' || data.status === 'draft')){

                            let users = BOOKING.users.map(function(item){
                                let it = Object.assign({}, item);
                                it.text = it.name + ' ( ' + it.phone + ' )';
                                return it;
                            });
                            $('.select-mailto', $html).select2({
                                data: users
                            });
                        }
                        self.bookingService.addEvent($html);
                    //}
                    $this.find('.form-content').append($html);
                    if( data.status === 'done' ){
                        $this.find('.form-content').find('#smartwizard').find('a[href="#step-2"]').trigger('click');
                    }
                    // let $print = $('#print-preview');
                    // if( $print.length ) {
                    //     $print.load(function(){
                    //         $print.height($print.contents().height());
                    //     });
                    // }
                });
                p.catch((e) => {
                    self.bookingService.trigger('pushNotification', 'Đã xảy ra lỗi. Vui lòng thử lại.', 'error');
                })
            });
            self.$modalForm.on('hidden.bs.modal', function(ev){
                let $this = $(this);
                $this.find('.form-content').html('');
            });

            $(document).on('click', '.forms-wizard a', function (ev) {
                let $this = $(this), $parent = $this.closest('li'), href = $this.attr('href');
                $('.forms-wizard').find('li').not($parent).removeClass('active');
                if( href === '#step-2' ){
                    setTimeout(function () {
                        let $print = $('#print-preview');
                        if( $print.length ) {
                            $print.height($print.contents().height());
                        }
                    }, 400)
                }
                $parent.addClass('active');
            })
        },
        initUiqid(){
            let n = Math.floor(Math.random() * 11);
            let k = Math.floor(Math.random() * 1000000);
            let m = n + k;
            return 'PO' + m;
        },

    }

    $.bookingServices.init();

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    let item = $('#bookingservices-' + id);
    item.find('.get-once').trigger('click');

});
