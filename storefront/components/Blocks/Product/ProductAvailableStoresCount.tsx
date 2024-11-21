import { TypeAvailability, TypeAvailabilityStatusEnum } from 'graphql/types';
import useTranslation from 'next-translate/useTranslation';
import { twJoin } from 'tailwind-merge';

type ProductAvailableStoresCountProps = {
    availability: TypeAvailability;
    availableStoresCount: number | null;
};

export const ProductAvailableStoresCount: FC<ProductAvailableStoresCountProps> = ({
    availability,
    availableStoresCount,
}) => {
    const { t } = useTranslation();

    return (
        <div
            className={twJoin(
                'text-sm',
                availability.status === TypeAvailabilityStatusEnum.InStock
                    ? 'text-availabilityInStock'
                    : 'text-availabilityOutOfStock',
            )}
        >
            {`${availability.name}${
                availability.status !== TypeAvailabilityStatusEnum.OutOfStock && availableStoresCount !== null
                    ? `, ${t('ready to ship immediately')} ${availableStoresCount !== 0 ? t('or at {{ count }} stores', { count: availableStoresCount }) : ''}`
                    : ''
            }`}
        </div>
    );
};
