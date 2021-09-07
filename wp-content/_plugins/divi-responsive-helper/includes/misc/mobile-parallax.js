jQuery(document).ready(function ($) {

    var pacDRHPhoneDevices = null !== navigator.userAgent.match(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/);

    if (pacDRHPhoneDevices) {
        pacDRHViewPort = function (elem) {
            elementTop = elem.offset().top, elementBottom = elementTop + elem.outerHeight(), viewportTop = $(window).scrollTop(), viewportBottom = viewportTop + $(window).height();
            return elementBottom > viewportTop && elementTop < viewportBottom;
        };
        $(window).scroll(pacDRHMobileParallax);
    }

    function pacDRHMobileParallax() {

        // True Parallax
        var pacTrueParallax = ".et_parallax_bg";
        if ($(pacTrueParallax).length > 0) {
            $(pacTrueParallax).addClass('pac_true_parallax')
            $('.pac_true_parallax').each(function () {
                var pacDRHParent = $(this).parent();
                if (pacDRHViewPort(pacDRHParent)) {
                    var pacDRHElementTop = pacDRHParent.offset().top;
                    pacDRHParallaxHeight = $(this).parent(".et_pb_fullscreen").length && $(window).height() > pacDRHParent.innerHeight() ? $(window).height() : pacDRHParent.innerHeight();
                    pacDRHHeight = .3 * $(window).height() + pacDRHParallaxHeight;
                    pacDRHTransform = "translate(0, " + .3 * ($(window).scrollTop() + $(window).height() - pacDRHElementTop) + "px)";
                    $('.pac_true_parallax').css({
                        "background-position": "top !important;",
                        "-moz-background-size": "cover !important;",
                        "-webkit-background-size": "cover !important;",
                        "background-size": "cover !important;",
                        "overflow": "hidden !important;",
                        "height": pacDRHHeight,
                        "-webkit-transform": pacDRHTransform,
                        "-moz-transform": pacDRHTransform,
                        "-ms-transform": pacDRHTransform,
                        "transform": pacDRHTransform,
                    });
                }
            });
        }
        // CSS Parallax
        var pacCSSParallax = ".et_parallax_bg.et_pb_parallax_css";
        if ($(pacCSSParallax).length > 0) {
            $(pacCSSParallax).removeClass('pac_true_parallax')
            $(pacCSSParallax).addClass('pac_css_parallax')
            $('.pac_css_parallax').each(function () {
                this.style.setProperty('background-attachment', 'fixed', 'important');
                this.style.setProperty('background-position', 'top', 'important');
                this.style.setProperty('-moz-background-size', 'cover', 'important');
                this.style.setProperty('-webkit-background-size', 'cover', 'important');
                this.style.setProperty('background-size', 'cover', 'important');
                this.style.setProperty('overflow', 'hidden', 'important');
                this.style.setProperty('height', '100%', 'important');
                this.style.removeProperty('-webkit-transform');
                this.style.removeProperty('-moz-transform');
                this.style.removeProperty('-ms-transform');
                this.style.removeProperty('transform');
            });
        }
    }
});
