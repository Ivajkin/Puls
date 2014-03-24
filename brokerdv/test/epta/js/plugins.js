// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Place any jQuery/helper plugins in here.

(function ($) {
    $.fn.indexOf = function (el) {
        out = -1;
        $(this).each(function (id) {
            //Do stuff for each element in matched set
            if (el == $(this).val()) {
                out = id;
                return out;
            }
        });
        return out;
    };
})(jQuery);
(function ($) {
    $.fn.getVal = function () {
        out = [];
        if ($(this).length)
            $(this).each(function (id) {
                //Do stuff for each element in matched set
                if ($(this).val().match(/([A-zА-я])|(\0)/) || !$(this).val()) out.push($(this).val());
                else {
                    tmp = parseFloat($j(this).val());
                    if (tmp % 1)
                        out.push(tmp);
                    else
                        out.push((tmp + 0));
                }
                //out.push($(this).val());
            });
        return out;
    }
})(jQuery);
