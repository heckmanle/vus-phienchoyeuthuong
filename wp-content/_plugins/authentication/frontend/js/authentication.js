jQuery(function ($) {
    $.authentication = {
        init () {
            this.$el = $('.wrapper-layout-authentication');
            this.firebase;
            this.init_firebase();
            this.backbone();
        },
        init_firebase () {
            // Initialize Firebase
            firebase.initializeApp(FIREBASE_CONFIG);
            firebase.analytics();
        },
        backbone () {
            const self = this;
            let authenBackbone = Backbone.View.extend({
                el: self.$el,
                events: {
                    'click .action-show-password': 'eventShowPassword',
                    'click .js-action-ajax': 'eventActionAjax',
                    'click .js-action-phone-ajax': 'eventSendCodeByPhone',
                    'click .js-re-send-code': 'eventReSendCode',
                },
                recaptchaVerifier: {},
                verificationID: '',
                initialize () {
                    this.initForm();
                    let url = (new URL(window.location)).searchParams;
                    if( url.has('view') && url.get('view') === 'login-verifycode' ){
                        $('.re-send-code').append('<div id="recaptcha-container"></div>');
                    }
                    if( $('#recaptcha-container').length ){
                        this.recaptchaFunction();
                    }

                },
                initForm () {
                    const _self = this;
                    $('form.frm-validation', self.$el).each(function(){
                        _self.formValidate($(this));
                    });
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

                eventShowPassword (ev) {
                    let $this = $(ev.currentTarget), {show} = $this.data(), $ele = $('#Password');
                    let type = $('#Password').attr('type');
                    if( show !== undefined ){
                        $ele = $(show);
                        type = $ele.attr('type');
                    }
                    $ele.attr('type', type === 'password' ? 'text' : 'password');
                },

                eventActionAjax (ev) {
                    let $this = $(ev.currentTarget),{send, method} = $this.data();
                    Applications.helpers.actionAjax({
                        data: Object.assign({}, send),
                        method: method,
                        funcSuccess (response, status, xhr) {
                            window.location.href = response.data._wp_http_referer;
                        },
                        funcError () {

                        },
                    })
                },

                recaptchaFunction () {
                    const _self = this
                    //firebase.auth().languageCode = 'vi';
                    setTimeout(function () {
                        _self.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                            'size': 'invisible',
                            'callback': function(response) {
                                // reCAPTCHA solved, allow signInWithPhoneNumber.
                                //console.log(response);
                            }
                        });

                        _self.recaptchaVerifier.render().then(function(widgetId) {
                            window.recaptchaWidgetId = widgetId;
                        });
                    }, 500);
                },

                eventReSendCode (ev) {
                    const _self = this;
                    let $this = $(ev.currentTarget),{type, func} = $this.data();
                    if( $this.hasClass('disabled') ){
                        return false;
                    }
                    if( !func ){
                        func = 'authentication_forgotpass';
                    }
                    let $count_down = $this.find('.count-down');
                    if( !$count_down.length ){
                        $this.append('<span class="ml-2 count-down"></span>');
                        $count_down = $this.find('.count-down');
                    }
                    let send = {
                        'action': 'handle_ajax',
                        'func': func,
                        'type' : 'email'
                    }
                    let count_down = 60;
                    if( type === 'phone_number' ){
                        send.type = type;
                        _self.sendCodeByPhone(send, 'post', false);

                    }else{
                        Applications.helpers.actionAjax({
                            data: Object.assign({}, send),
                            method: 'post',
                        })
                    }
                    let now = 1;
                    $this.addClass('disabled');
                    const countDownFunc = setInterval(function () {
                        let distance = count_down - now;
                        $count_down.html(`(${distance})`);
                        if (distance < 1) {
                            clearInterval(countDownFunc);
                            $this.removeClass('disabled');
                            $count_down.remove();
                        }
                        now++;
                    }, 1000)
                },

                sendCodeByPhone (send, method, redirect = true) {
                    const _self = this;
                    var provider = new firebase.auth.PhoneAuthProvider();
                    provider.verifyPhoneNumber(phoneNumber, _self.recaptchaVerifier)
                        .then(function (verificationId) {
                            _self.verificationID = verificationId;
                            Applications.helpers.actionAjax({
                                data: Object.assign({}, send, {verificationID: verificationId}),
                                method: method,
                                funcSuccess (response, status, xhr) {
                                    if( redirect === true ) {
                                        window.location.href = response.data._wp_http_referer;
                                    }
                                },
                                funcError () {

                                },
                            })
                        })
                        .then(function (phoneCredential) {
                            return firebase.auth().signInWithCredential(phoneCredential)
                        }).catch(function(e){
                        })
                },

                eventSendCodeByPhone (ev) {
                    let $this = $(ev.currentTarget),{send, method} = $this.data();
                    this.sendCodeByPhone(send, method);
                },
            });

            this.authenBackbone = new authenBackbone();
            window.authenBackbone = this.authenBackbone;
        },
    };
    $.authentication.init();
});
