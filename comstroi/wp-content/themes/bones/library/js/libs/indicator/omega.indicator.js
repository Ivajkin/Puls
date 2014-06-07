(function ($) {

  'use strict';

  /**
   * Renders a widget for displaying the current width of the browser.
   */
    $(document).ready(function(){
        var $indicator = $('<div class="omega-browser-width" />').appendTo('body').css({
            'position': 'fixed',
            'bottom': '0',
            'right': '0',
            'background-color': 'rgba(180, 180, 180, 0.49)',
            'color': 'black'
        });
        // Bind to the window.resize event to continuously update the width.
        $(window).bind('resize.omega-browser-width', function () {
            $indicator.text($(this).width() + 'px');
        }).trigger('resize.omega-browser-width');
    });

})(jQuery);
