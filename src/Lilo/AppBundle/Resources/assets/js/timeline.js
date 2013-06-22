(function ($) {
    var defaults = {};

    var methods = {
        init : function (options) {
            var settings = $.extend(defaults, options);
            var $this = $(this);
            $(this).data('timelineSettings', settings);

            _init($this);
            return this;
        }
    };

    $.fn.timeline = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || ! method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' +  method + ' does not exist on $.timeline');
        }
    };

    function _init($this) {
        var settings = $this.data('timelineSettings');
        var observed = [];

        $(window).scroll(function() {
            $this.find('.unread').each(function(i) {
                if (-1 == $.inArray(i, observed) && $(window).scrollTop() > $(this).offset().top) {
                    observed.push(i);

                    var eventData = $(this).attr('id').split('-');
                    $.post(
                        'exception' == eventData[0] ? settings.exceptionHandler : settings.messageHandler,
                        { id: eventData[1] },
                        function (data) {
                            $(this).removeClass('unread').addClass('read');
                        }
                    );
                }
            });
        });
    }
})(jQuery);
