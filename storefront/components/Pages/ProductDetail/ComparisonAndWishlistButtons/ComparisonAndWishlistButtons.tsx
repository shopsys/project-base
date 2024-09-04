import { ProductCompareButton } from 'components/Blocks/Product/ButtonsAction/ProductCompareButton';
import { ProductWishlistButton } from 'components/Blocks/Product/ButtonsAction/ProductWishlistButton';
import { TypeProductDetailFragment } from 'graphql/requests/products/fragments/ProductDetailFragment.generated';
import { useComparison } from 'utils/productLists/comparison/useComparison';
import { useWishlist } from 'utils/productLists/wishlist/useWishlist';

export type ComparisonAndWishlistButtonsProps = {
    product: TypeProductDetailFragment;
};

export const ComparisonAndWishlistButtons: FC<ComparisonAndWishlistButtonsProps> = ({ product }) => {
    const { isProductInComparison, toggleProductInComparison } = useComparison();
    const { toggleProductInWishlist, isProductInWishlist } = useWishlist();

    return (
        <div className="flex flex-wrap gap-x-4">
            <ProductCompareButton
                isWithText
                isProductInComparison={isProductInComparison(product.uuid)}
                toggleProductInComparison={() => toggleProductInComparison(product.uuid)}
            />
            <ProductWishlistButton
                isWithText
                isProductInWishlist={isProductInWishlist(product.uuid)}
                toggleProductInWishlist={() => toggleProductInWishlist(product.uuid)}
            />
        </div>
    );
};
