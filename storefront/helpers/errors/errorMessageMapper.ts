import { FlashMessageKeys } from './applicationErrors';
import { Translate } from 'next-translate';

const getErrorMessageTranslationString = (errorCode: FlashMessageKeys, t: Translate): string | undefined => {
    const ERROR_MESSAGES: Record<FlashMessageKeys, string> = {
        default: t('Unknown error.'),
        'cart-not-found': t('Cart not found.'),
        'max-allowed-limit': t('Max allowed limit reached.'),
        'packetery-address-id-invalid': t('Invalid Packetery address id.'),
        'invalid-credentials': t('Invalid credentials.'),
        'invalid-refresh-token': t('Invalid refresh token.'),
        'order-emails-not-sent': t('Automatic order emails was not sent.'),
        'order-empty-cart': t('Cart is empty.'),
        'personal-data-request-type-invalid': t('Invalid request type.'),
        'blog-category-not-found': t('Category not found.'),
        'image-type-invalid': t('Invalid image type.'),
        'image-size-invalid': t('Invalid image size.'),
        'order-not-found': t('Order not found.'),
        'personal-data-hash-invalid': t('Invalid hash.'),
        'product-price-missing': t('Product price is missing.'),
        'store-not-found': t('Store not found.'),
        'product-not-found': t('Product not found.'),
        'handling-with-logged-customer-comparison': t('Product not found.'),
        'comparison-not-found': t('Comparison not found.'),
        'compared-item-not-found': t('Compared product not found.'),
        'compared-item-already-exists': t('Compared product is already compared.'),
        'wishlist-not-found': t('Wishlist not found.'),
        'wishlist-item-already-exists': t('Product in wishlist already exists.'),
        'wishlist-item-not-found': t('Product in wishlist not found.'),
    };

    return ERROR_MESSAGES[errorCode];
};

export const getErrorMessage = (errorCode: FlashMessageKeys, t: Translate): string => {
    const translationString = getErrorMessageTranslationString(errorCode, t);

    return translationString !== undefined ? t(translationString) : t('Unknown error.');
};
