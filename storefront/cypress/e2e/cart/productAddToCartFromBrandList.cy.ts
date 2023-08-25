import { brandSencor, products, totalPrice, url } from 'fixtures/demodata';
import { checkProductInCart, checkTotalPriceInCart } from 'support/cart';
import { checkProductAndGoToCartFromCartPopupWindow } from 'support/cartPopupWindow';
import { addProductToCartFromProductList } from 'support/productList';

it('Brand list - Adding product to cart from brand list and check product in cart', () => {
    cy.visit(url.brandOverwiev);
    cy.getByDataTestId('blocks-simplenavigation-22').contains(brandSencor).click();
    addProductToCartFromProductList(products.helloKitty.catnum);
    checkProductAndGoToCartFromCartPopupWindow(products.helloKitty.namePrefixSuffix);
    checkProductInCart(products.helloKitty.catnum, products.helloKitty.namePrefixSuffix);
    checkTotalPriceInCart(totalPrice.cart1);
    cy.url().should('contain', url.cart);
});
