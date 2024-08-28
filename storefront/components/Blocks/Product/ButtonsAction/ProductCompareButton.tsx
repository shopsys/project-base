import { CompareIcon } from 'components/Basic/Icon/CompareIcon';
import useTranslation from 'next-translate/useTranslation';
import { twJoin } from 'tailwind-merge';
import { twMergeCustom } from 'utils/twMerge';

type ProductCompareButtonProps = {
    isWithText?: boolean;
    isProductInComparison: boolean;
    toggleProductInComparison: () => void;
};

export const ProductCompareButton: FC<ProductCompareButtonProps> = ({
    className,
    isWithText,
    isProductInComparison,
    toggleProductInComparison,
}) => {
    const { t } = useTranslation();

    return (
        <div
            title={isProductInComparison ? t('Remove product from comparison') : t('Add product to comparison')}
            className={twMergeCustom(
                'flex cursor-pointer items-center gap-2 p-2 text-link hover:text-linkHovered',
                className,
            )}
            onClick={toggleProductInComparison}
        >
            <CompareIcon
                className={twJoin('w-5', isProductInComparison ? 'text-activeIconFull' : '')}
                isFull={isProductInComparison}
            />
            {isWithText && (
                <span className="text-sm">{isProductInComparison ? t('Remove from comparison') : t('Compare')}</span>
            )}
        </div>
    );
};

ProductCompareButton.displayName = 'ProductCompareButton';
