(function ($) {

    Shopsys = window.Shopsys || {};
    Shopsys.pickUpPlaceSelection = Shopsys.pickUpPlaceSelection || {};

    var $pickUpPlaceInput = null;
    var TYPE_ZASILKOVNA = Shopsys.constant('\\Shopsys\\ShopBundle\\Model\\Transport\\Transport::TYPE_ZASILKOVNA');

    Shopsys.pickUpPlaceSelection.init = function ($container) {
        $pickUpPlaceInput = $('.js-pick-up-place-input');

        $container.filterAllNodes('.js-order-transport-input').change(Shopsys.pickUpPlaceSelection.onTransportChange);
        $container.filterAllNodes('.js-pick-up-place-city-post-code-autocomplete-input')
            .bind('keyup paste', Shopsys.pickUpPlaceSelection.onSearchAutocompleteInputChange);
        $container.filterAllNodes('.js-pick-up-place-button').click(Shopsys.pickUpPlaceSelection.onSelectPlaceButtonClick);
        $container.filterAllNodes('.js-pick-up-place-change-button').click(Shopsys.pickUpPlaceSelection.onChangeButtonClick);

    };

    Shopsys.pickUpPlaceSelection.onTransportChange = function (event) {
        var $transportInput = $('#js-window').data('transportInput');
        var isPickUpPlaceTransportType = Shopsys.pickUpPlaceSelection.isPickUpPlaceTransportType($(this).data('transport-type'));
        if (isPickUpPlaceTransportType && $(this).prop('checked') && ($transportInput === undefined || $transportInput[0] !== $(this)[0])) {
            Shopsys.pickUpPlaceSelection.showSearchWindow($(this));

            $(this).prop('checked', false);
            event.stopImmediatePropagation();
            event.preventDefault();
        }
    };

    Shopsys.pickUpPlaceSelection.showSearchWindow = function ($selectedTransportInput) {
        var pickUpPlaceInput = $('#transport_and_payment_form_pickUpPlace').val();
        var pickUpPlaceValue = (pickUpPlaceInput !== '') ? pickUpPlaceInput : null;
        var transportType = $selectedTransportInput.data('transport-type');

        Shopsys.ajax({
            url: $pickUpPlaceInput.data('pick-up-place-search-url'),
            dataType: 'html',
            data: {
                pickUpPlaceId: pickUpPlaceValue,
                transportType: transportType
            },
            success: function (data) {
                var $window = Shopsys.window({
                    content: data,
                    cssClass: 'window-popup--standard box-pick-up-place'
                });
                $window.data('transportInput', $selectedTransportInput);
                if ($('.js-pick-up-place-row').length == 0) {
                    $('.js-pick-up-place-autocomplete-results').toggle(false);
                }
            }
        });
    };

    Shopsys.pickUpPlaceSelection.onSearchAutocompleteInputChange = function () {
        var $searchContainer = $(this).closest('.js-pick-up-place-search');
        var $autocompleteResults = $searchContainer.find('.js-pick-up-place-autocomplete-results');

        $('.js-pick-up-place-autocomplete-results-detail').html('');

        Shopsys.timeout.setTimeoutAndClearPrevious('Shopsys.pickUpPlaceSelection.onSearchAutocompleteInputChange', function () {
            $autocompleteResults.show();
            Shopsys.ajax({
                url: $searchContainer.data('pick-up-place-autocomplete-url'),
                loaderElement: $autocompleteResults,
                dataType: 'html',
                method: 'post',
                data: {
                    searchQuery: $searchContainer.find('.js-pick-up-place-city-post-code-autocomplete-input').val(),
                    transportType: $('#js-window').data('transportInput').data('transport-type')
                },
                success: function (data) {
                    $autocompleteResults.html(data);
                    Shopsys.register.registerNewContent($autocompleteResults);

                    $('#js-window').resize();
                }
            });
        }, 200);
    };

    Shopsys.pickUpPlaceSelection.onSelectPlaceButtonClick = function () {
        var $button = $(this);
        $pickUpPlaceInput.val($button.data('id'));

        var $transportInput = $('#js-window').data('transportInput');
        if ($transportInput.prop('disabled') !== true) {
            $transportInput.prop('checked', true).change();
        }

        var $pickUpPlaceDetail = $('body').filterAllNodes('.js-pick-up-place-detail');
        $pickUpPlaceDetail.find('.js-pick-up-place-detail-name')
            .text($button.data('name'));

        $pickUpPlaceDetail.find('.js-pick-up-place-detail-address')
            .text($button.data('address'));

        $pickUpPlaceDetail.toggle($button.data('name').length > 0);

        Shopsys.windowFunctions.close();
    };

    Shopsys.pickUpPlaceSelection.onChangeButtonClick = function () {
        var $button = $(this);
        var $transportContainer = $button.closest('.js-order-transport');
        var $selectedTransportInput = $transportContainer.find('.js-order-transport-input');

        Shopsys.pickUpPlaceSelection.showSearchWindow($selectedTransportInput);
    };

    Shopsys.pickUpPlaceSelection.isPickUpPlaceTransportType = function (transportType) {
        return $.inArray(transportType, [TYPE_ZASILKOVNA]) !== -1;
    };

    Shopsys.register.registerCallback(Shopsys.pickUpPlaceSelection.init);

})(jQuery);
