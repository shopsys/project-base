import Register from 'framework/common/utils/Register';

class BestsellingProducts {
    static init () {
        $('.js-bestselling-products-load-more').click(function () {
            const $loadMoreButton = $(this);
            const $loadMoreItems = $loadMoreButton.closest('.js-bestselling-products').find('.js-bestselling-product');
            $loadMoreItems.slideDown('fast');
            $loadMoreButton.closest('.js-bestselling-products-load-more-container').slideUp('fast');
        });
    }
}

(new Register()).registerCallback(BestsellingProducts.init, 'BestsellingProducts.init');
