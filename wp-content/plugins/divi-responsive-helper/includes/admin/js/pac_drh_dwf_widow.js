(function ($) {
    $.fn.dwfWidow = function (gather) {
        if (typeof gather !== 'number') {
            gather = 1;
        }

        return $(this).each(function () {
            var $el = $(this),
                htmlEls = [],
                text,
                els,
                i,
                lastIndex,
                lngth,
                replaceRegex;
            if ($el.find(':input').length > 0) {
                return;
            }
            if ($el.find('a').length === 0) {
                text = $.trim($el.html());
                els = text.match(/<([A-Z][A-Z0-9]*)\b[^>]*>/gi);
                lngth = els !== null ? els.length : 0;
                for (i = 0; i < lngth; i++) {
                    htmlEls.push(els[i]);
                    text = text.replace(els[i], '__' + i + '__');
                }
                for (i = 0; i < gather; i++) {
                    lastIndex = text.lastIndexOf(' ');
                    if (lastIndex > 0) {
                        text = text.substring(0, lastIndex) + '&nbsp;' + text.substring(lastIndex + 1);
                    }
                }
                for (i = 0; i < lngth; i++) {
                    replaceRegex = new RegExp('__' + i + '__');
                    text = text.replace(replaceRegex, htmlEls[i]);
                }
                $el.html(text);
            }
        });
    };
})(jQuery);