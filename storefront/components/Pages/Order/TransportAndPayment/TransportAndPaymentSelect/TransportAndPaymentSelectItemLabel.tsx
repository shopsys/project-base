import { Image } from 'components/Basic/Image/Image';
import { OpeningHours } from 'components/Blocks/OpeningHours/OpeningHours';
import { TIDs } from 'cypress/tids';
import { TypeImageFragment } from 'graphql/requests/images/fragments/ImageFragment.generated';
import { Translate } from 'next-translate';
import useTranslation from 'next-translate/useTranslation';
import { twJoin } from 'tailwind-merge';
import { useFormatPrice } from 'utils/formatting/useFormatPrice';
import { isPriceVisible } from 'utils/mappers/price';
import { StoreOrPacketeryPoint } from 'utils/packetery/types';

type TransportAndPaymentSelectItemLabelProps = {
    name: string;
    price?: { priceWithVat: string; priceWithoutVat: string; vatAmount: string };
    daysUntilDelivery?: number;
    description?: string | null;
    image?: TypeImageFragment | null;
    pickupPlaceDetail?: StoreOrPacketeryPoint;
    isSelected: boolean;
};

export const TransportAndPaymentSelectItemLabel: FC<TransportAndPaymentSelectItemLabelProps> = ({
    name,
    price,
    daysUntilDelivery,
    description,
    image,
    pickupPlaceDetail,
    isSelected,
}) => {
    const { t } = useTranslation();
    const formatPrice = useFormatPrice();

    return (
        <div className="flex w-full flex-row items-center gap-5">
            <div
                className={twJoin('flex w-12  h-12 items-center', !image && 'hidden')}
                tid={TIDs.transport_and_payment_list_item_image}
            >
                <Image alt={image?.name ?? name} className="w-auto max-h-12" height={48} src={image?.url} width={48} />
            </div>

            <div className="flex flex-1 flex-col lg:flex-auto lg:basis-full lg:flex-row lg:items-center lg:gap-3">
                <div>
                    <div className={twJoin(isSelected && 'font-bold')} tid={TIDs.pages_order_selectitem_label_name}>
                        {name}
                    </div>

                    {description && <div className="text-textDisabled text-sm">{description}</div>}
                </div>

                {pickupPlaceDetail && (
                    <div>
                        <div className="text-textDisabled">{pickupPlaceDetail.name}</div>

                        <div className="text-textDisabled">
                            {pickupPlaceDetail.street}, {pickupPlaceDetail.postcode} {pickupPlaceDetail.city},{' '}
                            {pickupPlaceDetail.country.name}
                        </div>

                        <OpeningHours openingHours={pickupPlaceDetail.openingHours} />
                    </div>
                )}

                {daysUntilDelivery !== undefined && (
                    <div className="text-sm text-textAccent lg:ml-auto lg:basis-36 lg:text-right">
                        {getDeliveryMessage(daysUntilDelivery, !!pickupPlaceDetail, t)}
                    </div>
                )}
            </div>

            {price && isPriceVisible(price.priceWithVat) && (
                <div className="shrink-0 text-right text-sm font-bold lg:basis-20">
                    {formatPrice(price.priceWithVat)}
                </div>
            )}
        </div>
    );
};

const getDeliveryMessage = (daysUntilDelivery: number, isPersonalPickup: boolean, t: Translate) => {
    if (isPersonalPickup) {
        if (daysUntilDelivery === 0) {
            return t('Personal pickup today');
        }

        if (daysUntilDelivery < 7) {
            return t('Personal pickup in {{ count }} days', { count: daysUntilDelivery });
        }

        return t('Personal pickup in {{count}} weeks', {
            count: Math.ceil(daysUntilDelivery / 7),
        });
    }
    if (daysUntilDelivery === 0) {
        return t('Delivery today');
    }

    if (daysUntilDelivery < 7) {
        return t('Delivery in {{count}} days', {
            count: daysUntilDelivery,
        });
    }

    return t('Delivery in {{count}} weeks', {
        count: Math.ceil(daysUntilDelivery / 7),
    });
};
