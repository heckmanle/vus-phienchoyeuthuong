
jQuery(function ($){
    let Dashboard;
    Dashboard =  {
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
            this.$row_booking = _.template($('script#tpl_add_row_booking').html());
            this.$form_filter = $('#frm-filter-data-for-event');
            this.initForm();
            this.initEvent();
            this.initSelect2();
        },
        initEvent(){
            const self = this;
            self.$form_filter.trigger('submit');
            $(document).off('change', '.select-event-dashboard')
                .on('change', '.select-event-dashboard', function (ev){
                    self.$form_filter.trigger('submit');
                });
        },
        initSelect2() {
            $('.select2-dashboard').select2();
        },
        initForm(){
            const self = this;
            $(document).off('submit', '#frm-filter-data-for-event')
                .on('submit', '#frm-filter-data-for-event', function (ev){
                    ev.preventDefault();
                    const $this = $(this);
                    let serialize = $this.serialize();
                    let options = {
                        url: AJAX_URL,
                        type: 'post',
                        data: serialize,
                        dataType: 'json',
                        beforeSend: function () {
                            $('.table-preview-booking-of-event tbody').html('');
                            $('body').block(self.IMAGE_LOADING);
                        },
                        success: function(response, status, xhr){
                            let $event_area = $('.event-area'),
                                $even_processing = $('.even-processing'),
                                $booking_incurred = $('.booking-incurred');
                            let {area, booking_incurred, booking_done } = response.data;
                            if( area ) {
                                $booking_incurred.find('.widget-numbers').text(Applications.helpers.convertStringToMoney(booking_incurred));
                                //let percent = (booking_incurred * 100 / area).toFixed(1);
                                let percent = (booking_done / booking_incurred * 100).toFixed(1);
                                $even_processing.find('.widget-numbers').text(percent + '%');
                                $event_area.find('.widget-numbers').text(Applications.helpers.convertStringToMoney(area));
                            }else{
                                $booking_incurred.find('.widget-numbers').text(0);
                                $even_processing.find('.widget-numbers').text(0 + '%');
                                $event_area.find('.widget-numbers').text(0);
                            }
                            if(response.data.booking){
                                let html = '';
                                $.each(response.data.booking, function (key, item){
                                    let {id, votes, client, date, status, flag} = item;
                                    let client_name = client != null ? client.name : '';
                                    let row = self.$row_booking({
                                        id:id,
                                        votes: votes,
                                        client_name: client_name,
                                        status: status,
                                        flag: flag,
                                        date: date,
                                    })
                                    html += row;
                                });
                                $('.table-preview-booking-of-event tbody').append(html);
                            }else{
                                $('.table-preview-booking-of-event tbody').append('<div class="d-flex justify-content-center">Không có dữ liệu</div>');
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
                })
        },
    }
    Dashboard.init();
});
