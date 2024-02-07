import { CartIcon } from 'components/Basic/Icon/IconsSvg';
import { Loader } from 'components/Basic/Loader/Loader';
import { Button } from 'components/Forms/Button/Button';
import { Spinbox } from 'components/Forms/Spinbox/Spinbox';
import { DataTestIds } from 'cypress/dataTestIds';
import { CartItemFragmentApi } from 'graphql/generated';
import { GtmMessageOriginType, GtmProductListNameType } from 'gtm/types/enums';
import { twMergeCustom } from 'helpers/twMerge';
import { useAddToCart } from 'hooks/cart/useAddToCart';
import useTranslation from 'next-translate/useTranslation';
import dynamic from 'next/dynamic';
import { useRef, useState } from 'react';

const AddToCartPopup = dynamic(() =>
    import('components/Blocks/Product/AddToCartPopup').then((component) => component.AddToCartPopup),
);

type AddToCartProps = {
    productUuid: string;
    minQuantity: number;
    maxQuantity: number;
    gtmMessageOrigin: GtmMessageOriginType;
    gtmProductListName: GtmProductListNameType;
    listIndex: number;
};

export const AddToCart: FC<AddToCartProps> = ({
    productUuid,
    minQuantity,
    maxQuantity,
    gtmMessageOrigin,
    gtmProductListName,
    listIndex,
    className,
}) => {
    const spinboxRef = useRef<HTMLInputElement | null>(null);
    const { t } = useTranslation();
    const [changeCartItemQuantity, fetching] = useAddToCart(gtmMessageOrigin, gtmProductListName);
    const [popupData, setPopupData] = useState<CartItemFragmentApi | undefined>(undefined);

    const onAddToCartHandler = async () => {
        if (spinboxRef.current === null) {
            return;
        }

        const addToCartResult = await changeCartItemQuantity(productUuid, spinboxRef.current.valueAsNumber, listIndex);
        spinboxRef.current!.valueAsNumber = 1;
        setPopupData(addToCartResult?.addProductResult.cartItem);
    };

    return (
        <div className={twMergeCustom('flex items-stretch justify-between gap-2', className)}>
            <Spinbox
                defaultValue={1}
                id={productUuid}
                max={maxQuantity}
                min={minQuantity}
                ref={spinboxRef}
                size="small"
                step={1}
            />
            <Button
                className="py-2"
                dataTestId={DataTestIds.blocks_product_addtocart}
                isDisabled={fetching}
                name="add-to-cart"
                size="small"
                onClick={onAddToCartHandler}
            >
                {fetching ? <Loader className="w-4 text-white" /> : <CartIcon className="w-4 text-white" />}
                <span>{t('Add to cart')}</span>
            </Button>

            {!!popupData && (
                <AddToCartPopup addedCartItem={popupData} onCloseCallback={() => setPopupData(undefined)} />
            )}
        </div>
    );
};
