import { AddToCart } from 'components/Blocks/Product/AddToCart';
import { Button } from 'components/Forms/Button/Button';
import { ListedProductFragmentApi } from 'graphql/generated';
import useTranslation from 'next-translate/useTranslation';
import { useRouter } from 'next/dist/client/router';
import { GtmMessageOriginType, GtmProductListNameType } from 'gtm/types/enums';
import { twJoin } from 'tailwind-merge';

type ProductActionProps = {
    product: ListedProductFragmentApi;
    gtmProductListName: GtmProductListNameType;
    gtmMessageOrigin: GtmMessageOriginType;
    listIndex: number;
};

const TEST_IDENTIFIER = 'blocks-product-action';

const wrapperTwClass = 'rounded bg-greyVeryLight p-2';

export const ProductAction: FC<ProductActionProps> = ({ product, gtmProductListName, gtmMessageOrigin, listIndex }) => {
    const router = useRouter();
    const { t } = useTranslation();

    if (product.isMainVariant) {
        return (
            <div className={wrapperTwClass}>
                <Button
                    onClick={() =>
                        router.push(
                            {
                                pathname: '/products/[productSlug]',
                            },
                            {
                                pathname: product.slug,
                            },
                        )
                    }
                    name="choose-variant"
                    dataTestId={TEST_IDENTIFIER + '-choose-variant'}
                    className="!w-full"
                >
                    {t('Choose variant')}
                </Button>
            </div>
        );
    }

    if (product.isSellingDenied) {
        return <div className={twJoin('text-center', wrapperTwClass)}>{t('This item can no longer be purchased')}</div>;
    }

    return (
        <AddToCart
            className={twJoin('w-full', wrapperTwClass)}
            productUuid={product.uuid}
            minQuantity={1}
            maxQuantity={product.stockQuantity}
            gtmMessageOrigin={gtmMessageOrigin}
            gtmProductListName={gtmProductListName}
            listIndex={listIndex}
        />
    );
};
