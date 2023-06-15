import Ajax from 'framework/common/utils/Ajax';
import Register from 'framework/common/utils/Register';

export default class OrderRememberData {

    static delayedSaveData () {
        const $this = $(this);
        clearTimeout(OrderRememberData.delayedSaveDataTimer);
        OrderRememberData.delayedSaveDataTimer = setTimeout(function () {
            $this.trigger('change.orderRememberData');
        }, OrderRememberData.delayedSaveDataDelay);
    }

    static saveData (event) {
        clearTimeout(OrderRememberData.delayedSaveDataTimer);
        const $orderForm = $('#js-order-form');
        Ajax.ajaxPendingCall('Shopsys.orderRememberData.saveData', {
            type: 'POST',
            url: $orderForm.data('ajax-save-url'),
            data: $orderForm.serialize(),
            loaderElement: $(event.target)
        });
    }

    static init ($container) {
        $container.filterAllNodes('#js-order-form input, #js-order-form select, #js-order-form textarea')
            .on('change.orderRememberData', OrderRememberData.saveData);

        $container.filterAllNodes('#js-order-form input, #js-order-form textarea')
            .on('keyup.orderRememberData', OrderRememberData.delayedSaveData);
    }
}

OrderRememberData.delayedSaveDataTimer = null;
OrderRememberData.delayedSaveDataDelay = 200;

(new Register()).registerCallback(OrderRememberData.init, 'OrderRememberData.init');
