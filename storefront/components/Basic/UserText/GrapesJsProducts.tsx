import {
    ProductsSlider,
    VISIBLE_SLIDER_ITEMS_ARTICLE,
    VISIBLE_SLIDER_ITEMS_BLOG,
} from 'components/Blocks/Product/ProductsSlider';
import { SkeletonModuleProductListItem } from 'components/Blocks/Skeleton/SkeletonModuleProductListItem';
import { TypeProductsByCatnums } from 'graphql/requests/products/queries/ProductsByCatnumsQuery.generated';
import { GtmMessageOriginType } from 'gtm/enums/GtmMessageOriginType';
import { GtmProductListNameType } from 'gtm/enums/GtmProductListNameType';
import { createEmptyArray } from 'utils/arrays/createEmptyArray';
import { parseCatnums } from 'utils/parsing/grapesJsParser';
import { twMergeCustom } from 'utils/twMerge';

type GrapesJsProps = {
    rawProductPart: string;
    allFetchedProducts?: TypeProductsByCatnums | undefined;
    areProductsFetching: boolean;
    visibleSliderItems: number;
};

export const GrapesJsProducts: FC<GrapesJsProps> = ({
    rawProductPart,
    allFetchedProducts,
    areProductsFetching,
    visibleSliderItems,
}) => {
    const products = [];

    const productCatnums = parseCatnums(rawProductPart);

    for (const productCatnum of productCatnums) {
        const matchingProduct = allFetchedProducts?.productsByCatnums.find(
            (blogArticleProduct) => blogArticleProduct.catalogNumber === productCatnum,
        );

        if (matchingProduct) {
            products.push(matchingProduct);
        }
    }

    if (areProductsFetching) {
        return (
            <div className="flex">
                {createEmptyArray(4).map((_, index) => (
                    <SkeletonModuleProductListItem key={index} />
                ))}
            </div>
        );
    }

    if (!products.length) {
        return null;
    }

    const isBlog = visibleSliderItems === VISIBLE_SLIDER_ITEMS_BLOG;
    const isArticle = visibleSliderItems === VISIBLE_SLIDER_ITEMS_ARTICLE;

    return (
        <div
            className={twMergeCustom(
                'my-4',
                isBlog && products.length > VISIBLE_SLIDER_ITEMS_BLOG ? 'xl:my-9' : '',
                isArticle && products.length > VISIBLE_SLIDER_ITEMS_ARTICLE ? 'vl:my-9' : '',
            )}
        >
            <ProductsSlider
                gtmMessageOrigin={GtmMessageOriginType.other}
                gtmProductListName={GtmProductListNameType.other}
                products={products}
                variant={isBlog ? 'blog' : 'article'}
                visibleSliderItems={visibleSliderItems}
            />
        </div>
    );
};
