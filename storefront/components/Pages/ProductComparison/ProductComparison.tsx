import { ProductComparisonContent } from './ProductComparisonContent';
import { Heading } from 'components/Basic/Heading/Heading';
import { Loader } from 'components/Basic/Loader/Loader';
import { Webline } from 'components/Layout/Webline/Webline';
import { useGtmSliderProductListViewEvent } from 'gtm/hooks/productList/useGtmSliderProductListViewEvent';
import { useComparison } from 'hooks/comparison/useComparison';
import useTranslation from 'next-translate/useTranslation';
import { GtmProductListNameType } from 'gtm/types/enums';
import { InfoIcon } from 'components/Basic/Icon/IconsSvg';

export const ProductComparison: FC = () => {
    const { t } = useTranslation();

    const { comparison, fetching } = useComparison();

    const content = comparison?.products ? (
        <ProductComparisonContent productsCompare={comparison.products} />
    ) : (
        <div className="my-[75px] flex items-center">
            <InfoIcon className="mr-4 w-8" />

            <Heading type="h3" className="!mb-0">
                {t('Comparison does not contain any products yet.')}
            </Heading>
        </div>
    );

    useGtmSliderProductListViewEvent(comparison?.products, GtmProductListNameType.product_comparison_page);

    return (
        <Webline>
            {fetching ? (
                <div className="flex w-full justify-center py-10">
                    <Loader className="w-10" />
                </div>
            ) : (
                content
            )}
        </Webline>
    );
};
