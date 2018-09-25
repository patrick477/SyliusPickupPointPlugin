(function ( $ ) {
    'use strict';

    $.fn.extend({
        findPickupPoints: function () {
            return this.each(function() {
                let $element = $(this);
                let $container = $(this).closest('.item');
                let url = $element.data('pickup-point-provider-url');
                let csrfToken = $element.data('csrf-token');

                if(!url) {
                    return;
                }

                $element.api({
                    method: 'GET',
                    on: 'change',
                    cache: false,
                    url: url,
                    beforeSend: function (settings) {
                        settings.data = {
                            _csrf_token: csrfToken
                        };

                        removePickupPoints($container);
                        $container.addClass('loading');

                        return settings;
                    },
                    onSuccess: function (response) {
                        addPickupPoints($container, response);
                        console.log(response);
                    },
                    onFailure: function (response) {
                        console.log(response);
                    },
                    onComplete: function () {
                        $container.removeClass('loading');
                    }
                });
            });
        }
    });

    function removePickupPoints($container) {
        $container.find('.pickup-points').remove();
    }

    /**
     *
     * @param {object} $container
     * @param {array} pickupPoints
     */
    function addPickupPoints($container, pickupPoints) {
        let list = '<ul class="pickup-points">';

        pickupPoints.forEach(function (element) {
            list += '<li class="pickup-point">';
            list += '<div class="name">' + element.name + '</div>';
            list += '<div class="name">' + element.address + '</div>';
            list += '<div class="name">' + element.zipCode + ' ' + element.city + '</div>';
            list += '<div class="name">' + element.country + '</div>';
            list += '</li>';
        });

        list += '</ul>';

        $container.find('.content').append(list);
    }
})( jQuery );
