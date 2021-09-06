(function( $ ) {
    'use strict';
    $('.ppb-pro-gallery-item').click(function (ev){
        ev.preventDefault();
        let {cls} = $(this).data();
        $('#ppb-pro-lightgallery-js .' + cls + ' img').trigger('click');
        ev.stopPropagation();
    });
    lightGallery(document.getElementById('ppb-pro-lightgallery-js'));
})(jQuery);