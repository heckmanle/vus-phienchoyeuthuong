(function( $ ) {
	'use strict';
	_.templateSettings = {
		evaluate: /<#([\s\S]+?)#>/g,
		interpolate: /{{{([\s\S]+?)}}}/g,
		escape: /{{([^}]+?)}}(?!})/g
	};
	$.PBB = {
		init() {
			this.$el = $('.ppb-layout-product-create');
			this.$templatePostDetail = $('script#tpl-product-detail');
			this.$templateGalleryItem = $('script#tpl-gallery-item');
			this.$templatePostItem = $('script#tpl-product-item');
			this.backbone();
		},
		backbone() {
			const self = this;
			let _pbb = Backbone.View.extend({
				el: self.$el,
				template: {
					product_detail: _.template(self.$templatePostDetail.html()),
					gallery_item: _.template(self.$templateGalleryItem.html()),
					product_item: _.template(self.$templatePostItem.html()),
				},
				gallery: {
					idx: 0,
					files: [],
				},
				defaults: {
					id: '',
					product_title: '',
					updated: moment().format('YYYY/MM/DD'),
					product_description: '',
					product_seo_keywords: '',
					product_seo_description: '',
					product_status: 'draft',
					product_slug: '',
					edit_post_url: '',
					product_gallery: [],
					product_category: [],
				},
				events: {
					'click .btn-pro-create': 'eventShowPostDetail',
					'click .pbb-product-item': 'eventShowPostDetail',
					'click .js-edit-pro-title': 'eventTogglePostTitle',
					'keyup .input-pro-title': 'eventKeyPressProTitle',
					'change .pbb-js-pro-gallery-add input[type="file"]': 'eventGalleryAdd',
					'click .js-pro-gallery-item-remove': 'eventGalleryRemove',
					'click .pbb-section-pagination ul.pagination a.page-numbers': 'eventPage',
					'click .handle-actions .handlediv': 'eventToggleDiv',
					'click #pbb-btn-pro-action-delete': 'eventDelete',
				},
				initialize() {
					this.initEvents();
					this.initForm();
					this.render();
				},
				getToastrOption (){
					return {
						"closeButton": false,
						"debug": false,
						"newestOnTop": false,
						"progressBar": false,
						"positionClass": "toast-bottom-center",
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
				initEvents() {
				},
				initForm () {
					const _self = this;
					$('form.pbb-form-product').each(function(idx, ele){
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
						beforeSubmit: function(serialize, form, option) {
							if (_self.gallery.files.length) {
								Object.keys(_self.gallery.files).forEach(key => {
									let {file} = _self.gallery.files[key];
									serialize.push({name: 'gallery_files[' + key + ']', type: 'file', value: file});
								});
							}
						},
						beforeSend: function () {
							$('body').block(PBB_IMAGE_LOADING);
						},
						success: function(response, status, xhr){
							let {edit_post_url, id} = response.data;
							$('.pbb-builder-layout').removeClass('d-none');
							$('.pbb-builder-layout .pbb-drag-drop-layout').attr('href', edit_post_url);
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
							$('body').unblock(PBB_IMAGE_LOADING);
							return;
						}
					};
					self.xhr_abort = $form.ajaxSubmit(options);
				},
				resetGalleryDefault(){
					this.gallery.idx = 0;
					this.gallery.files = [];
				},
				async loadList() {
					let res = {};
					let options = {
						url: PBB_AJAX_URL,
						type: 'get',
						dataType: 'json',
						async: true,
						data: {action: 'pbb_handle_ajax', func: 'pbb_get_my_pages', page: PBB_GLOBAL.page},
						beforeSend: function (xhr, settings) {
							$('#pbb-product-tabs-content').block(PBB_IMAGE_LOADING);
							return;
						},
						error: function (xhr, status, errThrow) {

							return;
						},
						success: function (response, status, xhr) {
							res = response.data;
							return;
						},
						complete: function (xhr, status) {
							$('#pbb-product-tabs-content').unblock(PBB_IMAGE_LOADING);
							return;
						}
					}
					await $.ajax(options);
					return res;
				},

				async getPostDetail(id) {
					let res = {};
					let options = {
						url: PBB_AJAX_URL,
						type: 'get',
						dataType: 'json',
						async: true,
						data: {action: 'pbb_handle_ajax', func: 'pbb_get_page_by_id', id: id},
						beforeSend: function (xhr, settings) {
							//$('.pbb-form-product').block(PBB_IMAGE_LOADING);
							return;
						},
						error: function (xhr, status, errThrow) {

							return;
						},
						success: function (response, status, xhr) {
							res = response.data;
							return;
						},
						complete: function (xhr, status) {
							//$('.pbb-form-product').unblock(PBB_IMAGE_LOADING);
							return;
						}
					}
					await $.ajax(options);
					return res;
				},
				addEvents($ele) {
					const self = this;
					let $select_pro_status = $('select.product-status', $ele), {selectedDefault: proStatus} = $select_pro_status.data();
					$select_pro_status.select2({
						minimumResultsForSearch: Infinity,
					});
					$select_pro_status.val(proStatus).trigger('change.select2');
				},
				templateParse (type, data) {
					return this.template.hasOwnProperty(type) ? this.template[type](data) : '';
				},
				eventShowPostDetail(ev) {
					const _self = this;
					this.resetGalleryDefault();
					ev.preventDefault();
					$('.pbb-form-product').block(PBB_IMAGE_LOADING);
					let $this = $(ev.currentTarget), { id } = $this.data();
					let p = new Promise((resolve, reject) => {
						$('.pbb-pro-detail').html('');
						if( $this.hasClass('btn-pro-create') ){
							resolve([]);
						}else{
							let res = _self.getPostDetail(id);
							resolve(res);
						}
						$('.pbb-form-product').unblock(PBB_IMAGE_LOADING);
					});
					p.then(function(data) {
						data = Object.assign({}, _self.defaults, data);
						let html = _self.templateParse('product_detail', data);
						$('.pbb-pro-detail').html(html);
						_self.addEvents($('.pbb-pro-detail'));
					}, function(error){
						console.log(error);
					});
					p.catch((e) => {
						console.log(e);
					})
				},
				eventTogglePostTitle(ev) {
					ev.preventDefault();
					let $this = $(ev.currentTarget);
					$('.input-pro-title').toggleClass('d-none').focus();
					let _length = $('.input-pro-title').val().length;
					$('.input-pro-title')[0].setSelectionRange(_length, _length);
					$('.lbl-pro-title').toggleClass('d-none');
				},
				eventKeyPressProTitle(ev) {
					let $this = $(ev.currentTarget);
					let _val = $this.val();
					$('.lbl-pro-title').text(_val);
					$('.breadcrumb-pro-title').text(_val);
				},
				eventGalleryAdd(ev) {
					const _self = this;
					let $this = $(ev.currentTarget), files = ev.currentTarget.files, $parent = $this.closest('.pbb-pgi-add');
					let validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
					let _length = $('.pbb-pgi-content .pbb-pgi:not(.pbb-pgi-add)').length;
					if( _length >= PBB_GLOBAL.limit_file ){
						$parent.addClass('d-none');
						_self.trigger('pushNotification', 'The number of images is not more than 3', 'warning');
						return;
					}
					let html = '', i = 0;
					$.each(files, function (index, file) {
						if( i === PBB_GLOBAL.limit_file ){
							$parent.addClass('d-none');
							return;
						}
						html += _self.templateParse('gallery_item', {file: file, idx: _self.gallery.idx});
						_self.gallery.files.push({
							idx: _self.gallery.idx,
							file: file,
						});
						i++;
						_self.gallery.idx++;
					});
					$(html).insertBefore($parent);
					if( $('.rep-pgi-content .rep-pgi:not(.rep-pgi-add)').length >= PBB_GLOBAL.limit_file ){
						$parent.addClass('d-none');
					}
				},
				eventGalleryRemove(ev) {
					const _self = this;
					let $this = $(ev.currentTarget), $parents = $this.closest('.pbb-pgi-content'), $parent = $this.closest('.pbb-pgi'), $add = $parents.find('.pbb-pgi-add'), {idx} = $parent.data();
					let index = _self.gallery.files.findIndex(item => item.idx == idx);
					if( index >= 0 ) {
						_self.gallery.files.splice(index, 1);
					}
					$add.removeClass('d-none');
					$parent.remove();
				},

				eventPage (ev) {
					ev.preventDefault();
					let $this = $(ev.currentTarget), {paged} = $this.data();
					PBB_GLOBAL.page = paged;
					this.render();
				},

				eventToggleDiv (ev) {
					let $this = $(ev.currentTarget),
						ariaExpandedValue,
						p = $this.closest('.postbox'),
						id = p.attr('id');
					p.toggleClass('closed');
					ariaExpandedValue = !p.hasClass('closed');
					$this.attr('aria-expanded', ariaExpandedValue)
				},

				eventDelete(ev) {
					const _self = this;
					let $this = $(ev.currentTarget), {id} = $this.data();
					ev.preventDefault();
					this.createModal({
						id: 'modal-confirm-delete-page',
						title: 'Do you want to delete page?',
						funcYes: () => {
							let options = {
								url: PBB_AJAX_URL,
								type: 'get',
								dataType: 'json',
								async: true,
								data: {action: 'pbb_handle_ajax', func: 'pbb_delete_page_by_id', id: id},
								beforeSend: function (xhr, settings) {
									$('.pbb-form-product').block(PBB_IMAGE_LOADING);
									return;
								},
								error: function (xhr, status, errThrow) {
									let textError = self.getTextMessageByXHR(xhr, errThrow);
									_self.trigger('pushNotification', textError, 'error');
									return;
								},
								success: function (response, status, xhr) {
									_self.trigger('pushNotification', 'Success', 'success');
									_self.render();
									$('.pbb-pro-detail').html('<p class="alert_aligncenter">Please add new page or choose page for update ..</p>');
									$('#modal-confirm-delete-page').modal('hide');
								},
								complete: function (xhr, status) {
									$('.pbb-form-product').unblock(PBB_IMAGE_LOADING);
									return;
								}
							}
							$.ajax(options);
						}
					});
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

				render() {
					const _self = this;
					let p = new Promise((resolve, reject) => {
						let res = _self.loadList();
						resolve(res);
					});
					p.then(function(data) {
						let html = '';
						$.each(data.result, function (idx, item){
							html += _self.templateParse('product_item', Object.assign({}, _self.defaults, item));
						});
						$('#pbb-myposts ul').html(html);
						$('#pbb-myposts .pbb-section-pagination').html(data.pagination);
					}, function(error){
						console.log(error);
					});
					p.catch((e) => {
						console.log(e);
					})
				},
			});
			this.pbb = new _pbb();
			this.pbb.on('pushNotification', function(message, type) {
				toastr.options = this.getToastrOption();
				toastr[type](message, '');
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
					textError = 'An error occurred, please try again';
				}
			}else{
				textError = 'An error occurred, please try again';
			}
			return textError;
		},
	}
	$.PBB.init();
})( jQuery );
