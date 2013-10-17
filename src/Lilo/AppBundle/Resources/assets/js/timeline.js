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

        $('.event-class').click(function (e) {
            e.preventDefault();
            $(this).closest('.content').find('.environment').slideToggle(500);
            $(this).closest('.content').find('.trace').slideToggle(500);

            _markAsRead($(this).closest('.event'));
        });

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
                            $('#instance_label_' + e.data('instance')).html($('#instance_label_' + e.data('instance')).html() - 1);
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
                    if ($this.find('#people-' + button.data('id') + ' b').length > 0) {
                        $this.find('#people-' + button.data('id')).html(
                            $.trim($this.find('#people-' + button.data('id')).html())
                        );
                        $this.find('#people-' + button.data('id')).append(', ');
                    }
                    $this.find('#people-' + button.data('id')).append('<b class="text-success">' + settings.user + '</b>');

                    button.closest('.event').addClass('accepted');
                    button.remove();
                    $this.change();
                }
            );
            _markAsRead($(this).closest('.event'));
        });

        $this.find('.status-close').click(function () {
            var button = $(this);
            $.post(
                settings.statusHandler,
                { id: button.data('id'), status: 'closed' },
                function (data) {
                    if ($this.find('#people-' + button.data('id') + ' b').length > 0) {
                        $this.find('#people-' + button.data('id')).html(
                            $.trim($this.find('#people-' + button.data('id')).html())
                        );
                        $this.find('#people-' + button.data('id')).append(', ');
                    }
                    $this.find('#people-' + button.data('id')).append('<b class="text-danger">' + settings.user + '</b>');

                    if (button.parent().find('.status-accept').length > 0)
                        button.parent().find('.status-accept').remove();

                    button.closest('.event').addClass('closed');
                    button.remove();
                    $this.change();
                }
            );
            _markAsRead($(this).closest('.event'));
        });
    }

    function _markAsRead($this) {
        if ($this.hasClass('unread')) {
            if (-1 == $.inArray($this.attr('id'), observed)) {
                var eventData = $this.attr('id').split('-');
                $.post(
                    'exception' == eventData[0] ? settings.exceptionHandler : settings.messageHandler,
                    { id: eventData[1], status: 'accept' },
                    function (data) {
                        $this.removeClass('unread').addClass('read');
                        $('#instance_label_' + $this.data('instance')).html($('#instance_label_' + $this.data('instance')).html() - 1);
                    }
                );
            }
        }
    }
})(jQuery);
