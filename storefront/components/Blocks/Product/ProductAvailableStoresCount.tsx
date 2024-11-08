import { TypeAvailability, TypeAvailabilityStatusEnum } from 'graphql/types';
import useTranslation from 'next-translate/useTranslation';

type ProductAvailableStoresCountProps = {
    availability: TypeAvailability;
    isMainVariant: boolean;
    availableStoresCount: number;
};

export const ProductAvailableStoresCount: FC<ProductAvailableStoresCountProps> = ({
    availability,
    availableStoresCount,
    isMainVariant,
}) => {
    const { t } = useTranslation();

    if (isMainVariant) {
        return null;
    }

    return (
        <div className="text-sm text-availabilityInStock">
            {`${availability.name}${
                availability.status !== TypeAvailabilityStatusEnum.OutOfStock
                    ? `, ${t('ready to ship immediately')} ${availableStoresCount !== 0 ? t('or at {{ count }} stores', { count: availableStoresCount }) : ''}`
                    : ''
            }`}
        </div>
    );
};
