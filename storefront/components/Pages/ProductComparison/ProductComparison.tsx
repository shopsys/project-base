import { ProductComparisonContent } from './ProductComparisonContent';
import { Heading } from 'components/Basic/Heading/Heading';
import { Loader } from 'components/Basic/Loader/Loader';
import { Breadcrumbs } from 'components/Layout/Breadcrumbs/Breadcrumbs';
import { Webline } from 'components/Layout/Webline/Webline';
import { BreadcrumbFragmentApi } from 'graphql/generated';
import { useGtmSliderProductListViewEvent } from 'gtm/hooks/productList/useGtmSliderProductListViewEvent';
import { useComparison } from 'hooks/comparison/useComparison';
import useTranslation from 'next-translate/useTranslation';
import { GtmProductListNameType } from 'gtm/types/enums';
import { InfoIcon } from 'components/Basic/Icon/IconsSvg';

type ProductComparisonProps = {
    breadcrumb: BreadcrumbFragmentApi[];
};

export const ProductComparison: FC<ProductComparisonProps> = ({ breadcrumb }) => {
    const { t } = useTranslation();

    const { comparison, fetching } = useComparison();
    const comparedProducts = comparison?.products ?? [];

    const content =
        comparedProducts.length > 0 ? (
            <ProductComparisonContent productsCompare={comparedProducts} />
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
            <Breadcrumbs breadcrumb={breadcrumb} />
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