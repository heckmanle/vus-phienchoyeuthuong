(function( $ ) {
	'use strict';
	window.globalSettings.settingUnderScore();
	$.materialIcons = {
		init() {
			this.$el = $('.material-icons-wrapper');
			this.$templateRenderItems = $('script#tpl-render-items', this.$el);
			this.$templatePreviewItems = $('script#tpl-preview-items', this.$el);
			this.backbone();
		},
		backbone() {
			const self = this;
			let _materialIcons = Backbone.View.extend({
				el: self.$el,
				template: {
					render_items: _.template(self.$templateRenderItems.html()),
					preview_items: _.template(self.$templatePreviewItems.html())
				},
				events: {
					'change .material-label-upload input[name="material_icons[]"]': 'eventUploadFile',
					'click .material-icon-remove': 'eventDelete',
				},
				initialize(){
					this.render();
				},
				async getList() {
					const _self = this;
					let res = {};
					let options = {
						url: GS_AJAX_URL,
						type: 'get',
						dataType: 'json',
						async: true,
						data: {action: 'gs_handle_ajax', func: 'gs_get_material_icons'},
						beforeSend: function (xhr, settings) {
							$('.material-icons-list').block(GS_IMAGE_LOADING);
							return;
						},
						error: function (xhr, status, errThrow) {
							_self.trigger('pushNotification', window.globalSettings.getTextMessageByXHR(xhr, errThrow), 'error');
							return;
						},
						success: function (response, status, xhr) {
							res = response.data;
							return;
						},
						complete: function (xhr, status) {
							$('.material-icons-list').unblock(GS_IMAGE_LOADING);
							return;
						}
					}
					await $.ajax(options);
					return res;
				},
				templateParse (type, data) {
					return this.template.hasOwnProperty(type) ? this.template[type](data) : '';
				},
				async uploadFile(file, index){
					const _self = this;
					let res;
					let formData = new FormData();
					formData.append('material_icons', file);
					formData.append('action', 'gs_handle_ajax');
					formData.append('func', 'gs_upload_file');
					await $.ajax({
						xhr: function() {
							let xhr = $.ajaxSettings.xhr();
							if (xhr.upload) {
								xhr.upload.addEventListener('progress', function(evt) {
									var percent = (evt.loaded / evt.total) * 100;
									$("#modal-preview-images .image-item-" + index + " .progress-bar").width(percent + '%');
								}, false);
							}
							return xhr;
						},
						type: 'POST',
						url: GS_AJAX_URL,
						data: formData,
						contentType: false,
						cache: false,
						processData:false,
						beforeSend: function(){
							$("#modal-preview-images .image-item-" + index + " .progress-bar").width('0%');
						},
						error:function(xhr, status, errThrow){
							$("#modal-preview-images .image-item-" + index + " .progress-bar").width('0%');
							_self.trigger('pushNotification', window.globalSettings.getTextMessageByXHR(xhr, errThrow));
						},
						success: function(resp){
							$("#modal-preview-images .image-item-" + index + " .progress-bar").width('100%');
							res = true;
						}
					});
					return res;
				},
				eventUploadFile(ev) {
					const _self = this;
					let $this = $(ev.currentTarget), files = ev.currentTarget.files;
					let _validFileExtensions = ["image/jpg", "image/jpeg", "image/bmp", "image/gif", "image/png", "image/svg+xml"];
					let blnValid = false;
					$.each(files, function (index, file) {
						let fileType = file.type;
						if( !_validFileExtensions.includes(fileType) ){
							blnValid = true;
							return;
						}
					});
					if( blnValid ){
						_self.trigger('pushNotification', 'File invalid image', 'error')
						return;
					}
					window.globalSettings.createModal({
						id: 'modal-preview-images',
						title: '',
						modalClass: 'modal-xl',
						header: false,
						footer: false,
						bodyHtml: _self.templateParse('preview_items', {files}),
					});
					const UploadFiles = async _ => {
						for (let index = 0; index < files.length; index++) {
							const file = files[index];
							let res = await _self.uploadFile(file, index)
							if( index === files.length - 1 ) {
								$('#modal-preview-images').modal('hide');
								_self.render();
							}
						}
					}
					UploadFiles();
				},
				eventDelete(ev) {
					const _self = this;
					let $this = $(ev.currentTarget), {url} = $this.data();
					window.globalSettings.createModal({
						id: 'modal-delete-image',
						title: 'Do you want to delete icon?',
						funcYes() {
							$.ajax({
								url: GS_AJAX_URL,
								data: {action: 'gs_handle_ajax', func: 'gs_material_icons_delete', icon: url},
								beforeSend: function (xhr, settings) {
									$('#modal-delete-image').block(GS_IMAGE_LOADING);
								},
								error:function(xhr, status, errThrow){
									_self.trigger('pushNotification', window.globalSettings.getTextMessageByXHR(xhr, errThrow));
								},
								success: function(response, status, xhr){
									$this.parent().remove();
									$('#modal-delete-image').modal('hide');
								},
								complete: function(xhr, status){
									$('#modal-delete-image').unblock(GS_IMAGE_LOADING);
								}
							});
						}
					});
				},
				render() {
					const _self = this;
					let p = new Promise((resolve, reject) => {
						let res = _self.getList();
						resolve(res);
					});
					p.then(function(data) {
						let html = _self.templateParse('render_items', data);
						$('.material-icons-items').html(html);
					}, function(error){
						console.log(error);
					});
					p.catch((e) => {
						console.log(e);
					})
				}
			});
			this.materialIcons = new _materialIcons();
			this.materialIcons.on('pushNotification', function(message, type) {
				toastr.options = window.globalSettings.getToastrOption();
				toastr[type](message, '');
			});
		},
	}
	$.materialIcons.init();
})( jQuery );
