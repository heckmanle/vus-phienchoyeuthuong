(function( $ ) {
	'use strict';
	window.globalSettings = {
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
})( jQuery );
