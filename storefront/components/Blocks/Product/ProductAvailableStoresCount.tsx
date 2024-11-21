import { TypeAvailability, TypeAvailabilityStatusEnum } from 'graphql/types';
import useTranslation from 'next-translate/useTranslation';

type ProductAvailableStoresCountProps = {
    availability: TypeAvailability;
    availableStoresCount: number | null;
};

export const ProductAvailableStoresCount: FC<ProductAvailableStoresCountProps> = ({
    availability,
    availableStoresCount,
}) => {
    const { t } = useTranslation();

    if (availableStoresCount === null) {
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
