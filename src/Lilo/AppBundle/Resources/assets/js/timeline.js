(function ($) {
    var defaults = {};
    var observed = [];

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

        $(window).scroll(function() {
            $this.find('.unread').each(function(i) {
                var e = $(this);
                if (-1 == $.inArray(e.attr('id'), observed) && $(window).scrollTop() > e.offset().top + e.height()) {
                    observed.push(e.attr('id'));

                    var eventData = e.attr('id').split('-');
                    $.post(
                        'exception' == eventData[0] ? settings.exceptionHandler : settings.messageHandler,
                        { id: eventData[1], status: 'accept' },
                        function (data) {
                            e.removeClass('unread').addClass('read');
                        }
                    );
                }
            });
        });

        $(window).unload(function() {
            $this.find('.unread').each(function(i) {
                var e = $(this);
                if ($(window).scrollTop() < e.offset().top && $(window).scrollTop() + $(window).height() > e.offset().top + e.height()) {
                    var eventData = e.attr('id').split('-');

                    $.ajax({
                        async: false,
                        type: 'POST',
                        url: 'exception' == eventData[0] ? settings.exceptionHandler : settings.messageHandler,
                        data: { id: eventData[1] }
                    });
                }
            });
        });

        $this.find('.status-accept').click(function () {
            var button = $(this);
            $.post(
                settings.statusHandler,
                { id: button.data('id'), status: 'accepted' },
                function (data) {
                    if ($this.find('#people-' + button.data('id') + ' b').length > 0)
                        $this.find('#people-' + button.data('id')).append(', ');
                    $this.find('#people-' + button.data('id')).append('<b class="text-success">' + settings.user + '</b>');

                    button.remove();
                }
            );
        });

        $this.find('.status-close').click(function () {
            var button = $(this);
            $.post(
                settings.statusHandler,
                { id: button.data('id'), status: 'closed' },
                function (data) {
                    if ($this.find('#people-' + button.data('id') + ' b').length > 0)
                        $this.find('#people-' + button.data('id')).append(', ');
                    $this.find('#people-' + button.data('id')).append('<b class="text-danger">' + settings.user + '</b>');

                    button.remove();
                }
            );
        });
    }
})(jQuery);