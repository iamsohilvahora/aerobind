(function ($) {

  jQuery.each(['show', 'hide'], function (i, ev) {

    var el = jQuery.fn[ev];

    jQuery.fn[ev] = function () {

      this.trigger(ev);
      return el.apply(this, arguments);
    };

  });

})(jQuery);

(function ( $ ) {

	"use strict";

	jQuery(function () {

		setTimeout(function(){

			jQuery('.entry-content').on('show', function(){
				jQuery(window).trigger('resize');
			});

		}, 1000);

	});

}(jQuery));