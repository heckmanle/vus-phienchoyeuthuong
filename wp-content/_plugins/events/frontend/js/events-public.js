jQuery(function ($){
	let Events = {
		init: function (){
			this.initForm();
			this.initEvent();
			this.initDatepicker();
			this.IMAGE_LOADING = {
				message: '<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
				css: {border: '', padding: 'none', width: '40px', height: '40px'}

			};

			_.templateSettings = {
				evaluate: /<#([\s\S]+?)#>/g,
				interpolate: /{{{([\s\S]+?)}}}/g,
				escape: /{{([^}]+?)}}(?!})/g
			};

			this.$row_form_event = _.template($('script#tpl_form_event').html());

			this.default_data_form = {
				id: '',
				event_name: '',
				event_desc: '',
				event_customer_role: '',
				event_manage_user: '',
				event_start: '',
				event_end: '',
				event_location: '',
				event_status: '',
			};
			this.initModal();
		},
		initSelect2(){
			$('.select2-event').select2();
		},
		initModal(){
			const self = this;
			$(document).off('show.bs.modal', '#exampleModalLongDetail')
				.on('show.bs.modal', '#exampleModalLongDetail', function (ev) {
					let target = $(ev.relatedTarget);
					$('#frm-handle-events', $(this)).html('');
					if(target.hasClass('edit-event')){
						let {send} = target.data();
						let {id, event_name, event_desc, event_customer_role, event_manage_user, event_start, event_end, event_location, event_status} = send;
						let row = self.$row_form_event({
							id: id,
							event_name: event_name,
							event_desc: event_desc,
							event_customer_role: event_customer_role,
							event_manage_user: event_manage_user,
							event_start: event_start,
							event_end: event_end,
							event_location: event_location,
							event_status: event_status
						});
						$('.modal-body', $(this)).append(row);
						self.initDatepicker();
						self.initSelect2();
					}else{
						let row = self.$row_form_event(self.default_data_form);
						$('.modal-body', $(this)).append(row);
						self.initDatepicker();
						self.initSelect2();
					}
				});
			$(document).off('hidden.bs.modal', '#exampleModalLongDetail')
				.on('hidden.bs.modal', '#exampleModalLongDetail', function (){
					$('.modal-body', $(this)).html('');
				});
		},
		initDatepicker(){
			$("#event_start").datepicker({
				dateFormat : "dd/mm/yy",
			});
			$("#event_end").datepicker({
				dateFormat : "dd/mm/yy",
			});
		},
		initForm(){
			const self = this;
			$(document).off('submit', '#frmAddEvent')
				.on('submit', '#frmAddEvent', function (ev){
					ev.preventDefault();
					const $this = $(this);
					let serialize = $this.serialize();
					let options = {
						url: AJAX_URL,
						type: 'post',
						data: serialize,
						dataType: 'json',
						beforeSend: function () {
							$('body').block(self.IMAGE_LOADING);
						},
						success: function(response, status, xhr){
							if(status == 'success'){
								window.location.reload();
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
		initEvent() {
			const self = this;
			$(document).off('change', '.checkall').on('change', '.checkall', function (ev){
					let $this = $(ev.currentTarget);
					let checked = $this.prop('checked');
					$('.check-item').prop('checked', checked);
			});

			$(document).off('click', '.btn-delete-events').on('click', '.btn-delete-events', function (ev){
					ev.preventDefault();
					let atLeastOneIsChecked = $('input[name="events[]"]:checked');
					let events = [];
					if(atLeastOneIsChecked.length > 0){
						$.each(atLeastOneIsChecked, function (key, value){
							events.push($(value).val());
						});
						if(events.length > 0){
							let send = {
								action: 'handle_ajax',
								func: 'delete_events',
								events: events
							}
							let args_modal = {
								id: 'modal-delete-events-confirm',
								title: 'Bạn có chắc muốn xóa không ?',
								bodyHtml: 'Bảo đảm không có hoạt động nào đang còn hiệu lực với sự kiện muốn xóa.',
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
														// ...
													})
												}
												if(data_success.length > 0){
													$.each(data_success, function (key, item) {
														let row = $('.event-' + item);
														if(row){
															row.css("background-color", "orange");
															setTimeout(function (ev){
																row.remove();
															},300)
														}
													})
												}
												$('#modal-delete-events-confirm').modal('hide');
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
	Events.init();

	$("#event_customer_role").select2({
		dropdownParent: $("#exampleModalLongDetail")
	});
	$("#event_location").select2({
		dropdownParent: $("#exampleModalLongDetail")
	});
	$("#event_manage_user").select2({
		dropdownParent: $("#exampleModalLongDetail")
	});





});