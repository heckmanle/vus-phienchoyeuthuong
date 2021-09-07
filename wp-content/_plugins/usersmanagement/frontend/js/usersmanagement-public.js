jQuery(function( $ ) {
	'use strict';
	Applications.helpers.translateValidate();
	$.userManagement = {
		init () {
			this.$el = $('.wrapper-layout-user-management');
			this.$form = $('form.form-validation', this.$el);
			this.backbone();
		},

		backbone () {
			const self = this;
			let userMngBackbone = Backbone.View.extend({
				el: self.$el,
				fileAvatar: {},
				dataTable: {},
				option_toastr: Applications.helpers.getToastrOption(),
				events: {
					'click .action-show-password': 'eventShowPassword',
					'click .js-action-ajax': 'eventActionAjax',
					'click .js-action-phone-ajax': 'sendCodeByPhone',
					'click .js-re-send-code': 'eventReSendCode',
					'click .js-action-delete': 'eventDelete',
					'change #table-users .check-all': 'eventCheckAll',
					'change #table-users .check-row': 'eventCheckRow',
				},

				initialize() {
					const _self = this;
					this.initForm();
					this.initEvent();
					this.avatar();
					if( $('#table-users').length ) {
						setTimeout(function () {
							_self.dataTable = window._jq("#table-users").DataTable({responsive: !0});
						}, 250);
					}
				},
				initForm() {
					const _self = this;
					$('form.form-validation', self.$el).each(function () {
						_self.formValidate($(this));
					});
				},
				formValidate($form) {
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
						errorPlacement: function (error, $element) {
							// Add the `help-block` class to the error element
							error.addClass("help-block");
							if ('$label' in $element[0]) {
								error.insertAfter($element[0].$label);
							} else if ($element.prop("type") === "checkbox") {
								error.insertAfter($element.parent("label"));
							} else {
								error.insertAfter($element);
							}
							return false;
						},
						invalidHandler: function (ev) {
							let validator = $form.data('validator');
							let elements = validator.invalidElements();

							if (validator.errorList.length == 0) {
								validator.errorList = [];
								$form.trigger('submit.validate');
								return;
							}
							return;
						},
						submitHandler: function (form, ev) {
							let $form = $(form);
							let validator = this;
							return _self.formSubmit($form, validator, ev);
						}
					};
					$validation.each(function (idx, ele) {
						let rules = {},
							$element = $(ele),
							dataSet = $element.data(),
							type = ele.getAttribute("type"),
							method, value, msg;

						for (method in $.validator.methods) {
							let method_name = method.charAt(0).toUpperCase() + method.substring(1).toLowerCase();
							value = $element.data("rule" + method_name);
							msg = $element.data("msg" + method_name);
							// Cast empty attributes like `data-rule-required` to `true`
							if (dataSet.hasOwnProperty("rule" + method_name)) {
								if (!validationRules.rules.hasOwnProperty(ele.id)) {
									validationRules.rules[ele.id] = [];
									validationRules.messages[ele.id] = [];
								}
								validationRules.rules[ele.id][method] = value;
								validationRules.messages[ele.id][method] = msg || MESSAGES_VALIDATORS[method];
							}
						}
					});
					let validator = $form.data('validator');
					if (validator) {
						$form
							.off(".validate")
							.removeData("validator")
							.find(".validate-equalTo-blur")
							.off(".validate-equalTo")
							.removeClass("validate-equalTo-blur");
					}
					$form.validate(validationRules);
				},
				formSubmit($form, validator, ev) {
					const _self = this;
					let options = {
						dataType: 'json',
						beforeSubmit: function (serialize, form, option) {
							$form.find('.message-notification').html('');
							if( _self.fileAvatar ){
								let find_file = serialize.findIndex(item => item.name === 'file_upload');
								if( Number(find_file) !== -1 ) {
									serialize[find_file].value = _self.fileAvatar;
								}
							}
						},
						beforeSend: function () {
							$('body').block(IMAGE_LOADING);
						},
						success: function (response, status, xhr) {
							window.onbeforeunload = null;

							if (response.data.hasOwnProperty('_wp_http_referer') && response.data._wp_http_referer != '') {
								window.location.href = response.data._wp_http_referer;
							} else {
								window.location.reload();
							}
							return;
						},
						error: function (xhr, status, errThrow) {
							let textError = Applications.helpers.getTextMessageByXHR(xhr, errThrow);
							$form.find('.message-notification').html(Applications.helpers.renderAlert(textError));
							return;
						},
						complete: function (xhr, status) {
							$('body').unblock(IMAGE_LOADING);
							//window.location.href = "/usersmanagement/?view=listing";
							return;
						}
					};
					self.xhr_abort = $form.ajaxSubmit(options);

				},

				initEvent () {

				},

				avatar () {
					const _self = this;
					let $userPictureModal = $('#modal-user-picture');
					let $notification = $('.message-notification', $userPictureModal);
					let $form_avatar = $userPictureModal.find('form');
					let $image = $('.cropper-image img#image');
					let options = {
						aspectRatio: 1 / 1,
						minContainerWidth: 400,
						minContainerHeight: 400,
						//dragMode: 'move',
						//zoomOnWheel: true,
						//viewMode: 3,
					};
					// Import image
					let $inputImage = $('#picture-upload');
					let URL = window.URL || window.webkitURL;
					let blobURL;
					let file_default;
					$image.on().cropper(options);
					if (URL) {
						$inputImage.change(function () {
							let files = this.files;
							let file;

							if (!$image.data('cropper')) {
								return;
							}

							if (files && files.length) {
								file = files[0];
								file_default = file;
								if (/^image\/\w+$/.test(file.type)) {
									blobURL = URL.createObjectURL(file);
									$image.one('built.cropper', function () {
										// Revoke when load complete
										URL.revokeObjectURL(blobURL);
									}).cropper('reset').cropper('replace', blobURL);
									$inputImage.val('');
									$notification.find('.notification').remove();
									//$('#range').data('ionRangeSlider').reset();
								} else {
									window.alert('Please choose an image file.');
								}
							}
						});
					} else {
						$inputImage.prop('disabled', true).parent().addClass('disabled');
					}
					// $('#range').ionRangeSlider({
					// 	min: 0,
					// 	max: 100,
					// 	from: 0,
					// 	onChange: function(data){
					// 		let zoom = data.from;
					// 		zoom = zoom / 10;
					// 		if( $('.cropper-image .cropper-container').length ) {
					// 			$image.cropper('zoomTo', zoom.toFixed(1));
					// 		}
					// 	}
					// });

					$userPictureModal.off('hidden.bs.modal').on('hidden.bs.modal', function (ev) {
						$image.cropper('destroy').cropper('replace', '');
						$image.data('cropper').options.aspectRatio = 1 / 1;
						$image.data('cropper').options.minContainerWidth = 400;
						$image.data('cropper').options.minContainerHeight = 400;
					});

					function dataURLtoFile(dataurl, filename) {
						let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
							bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
						while (n--) {
							u8arr[n] = bstr.charCodeAt(n);
						}
						return new File([u8arr], filename, {type: mime});
					}

					$('#btn-save-picture', $userPictureModal).off('click').on('click', function (ev) {
						ev.preventDefault();
						let result = $image.cropper('getCroppedCanvas');
						if (typeof result.toDataURL === 'function') {
							let img_b64 = result.toDataURL(file_default.type);
							$('#user-avatar').attr('src', img_b64);
							_self.fileAvatar = dataURLtoFile(img_b64, file_default.name);
						}
					});
				},
				eventShowPassword (ev) {
					let $this = $(ev.currentTarget), {show} = $this.data();
					let $ele = $(show), type = $ele.attr('type');
					$ele.attr('type', type === 'password' ? 'text' : 'password');
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
						id: 'modal-user-confirm',
						title: 'Bạn có chắc xoá người dùng được chọn?',
						body: true,
						bodyHtml: '',
						funcYes: function () {
							let options = {
								data: Object.assign({}, send, {id: id}),
								method: 'POST',
								beforeSend: function(xhr, settings) {
									$('#modal-user-confirm .modal-body').html('');
								},
								funcSuccess: function(response, status, xhr){
									let {users} = response.data;
									$('#modal-user-confirm').modal('hide');
									$('.js-action-delete').addClass('d-none');
									toastr.options = _self.option_toastr;
									toastr['success'](response.data.message, '');
									_self.dataTable.clear().draw();
									_self.dataTable.rows.add(users);
									_self.dataTable.columns.adjust().draw();
								},
								funcError: function(xhr, status, errThrow){
									let textError = Applications.helpers.getTextMessageByXHR(xhr, errThrow);
									textError = Applications.helpers.renderAlert(textError);
									$('#modal-user-confirm .modal-body').html(textError);
								}
							}
							Applications.helpers.actionAjax(options);
						},
						args: []
					};
					Applications.helpers.createModal(args_modal);
				},
			});

			this.userBackbone = new userMngBackbone();
		}
	}
	$.userManagement.init();
});
