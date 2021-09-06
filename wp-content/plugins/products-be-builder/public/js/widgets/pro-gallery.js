(function( $ ) {
    'use strict';
    $('.pro-gallery-item').click(function (ev){
        ev.preventDefault();
        let {cls} = $(this).data();
        $('#pro-lightgallery-js .' + cls + ' img').trigger('click');
        ev.stopPropagation();
    });
    lightGallery(document.getElementById('pro-lightgallery-js'));
})(jQuery);