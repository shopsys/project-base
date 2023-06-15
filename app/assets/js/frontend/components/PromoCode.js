import Ajax from 'framework/common/utils/Ajax';
import Window from '../utils/Window';
import Register from 'framework/common/utils/Register';
import constant from '../utils/constant';
import { KeyCodes } from 'framework/common/utils/KeyCodes';
import Translator from 'bazinga-translator';

const PROMOCODE_SUBMIT_BUTTON_ID = '#js-promo-code-submit-button';

export default class PromoCode {

    constructor ($promoCodeInput) {
        this.$promoCodeInput = $promoCodeInput;
    }

    applyPromoCode (event, promoCode) {
        const code = promoCode.$promoCodeInput.val();

        if (code !== '') {
            const _this = this;
            const data = {};

            data[constant('\\App\\Controller\\Front\\PromoCodeController::PROMO_CODE_PARAMETER')] = code;
            Ajax.ajax({
                loaderElement: PROMOCODE_SUBMIT_BUTTON_ID,
                url: promoCode.$promoCodeInput.data('apply-code-url'),
                dataType: 'json',
                method: 'post',
                data: data,
                success: _this.onApplyPromoCode
            });
        } else {
            // eslint-disable-next-line no-new
            new Window({
                content: Translator.trans('Please enter promo code.')
            });
        }
    }

    onApplyPromoCode (response) {
        if (response.result === true) {
            document.location.reload();
        } else {
            // eslint-disable-next-line no-new
            new Window({
                content: response.message
            });
        }
    }

    static init ($container) {
        const $promoCodeSubmitButton = $container.filterAllNodes(PROMOCODE_SUBMIT_BUTTON_ID);
        const $promoCodeInput = $container.filterAllNodes('#js-promo-code-input');
        const promoCode = new PromoCode($promoCodeInput);

        $promoCodeSubmitButton.click((event) => promoCode.applyPromoCode(event, promoCode));
        $promoCodeInput.keypress(function (event) {
            if (event.keyCode === KeyCodes.ENTER) {
                promoCode.applyPromoCode(event, promoCode);
                return false;
            }
        });
    }
}

(new Register()).registerCallback(PromoCode.init, 'PromoCode.init');
