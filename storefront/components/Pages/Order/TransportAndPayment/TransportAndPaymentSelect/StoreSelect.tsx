import { TransportAndPaymentListItem } from './TransportAndPaymentListItem';
import { Radiobutton } from 'components/Forms/Radiobutton/Radiobutton';
import { TransportAndPaymentSelectItemLabel } from 'components/Pages/Order/TransportAndPayment/TransportAndPaymentSelect/TransportAndPaymentSelectItemLabel';
import { TypeListedStoreConnectionFragment } from 'graphql/requests/stores/fragments/ListedStoreConnectionFragment.generated';
import { useMemo } from 'react';
import { mapConnectionEdges } from 'utils/mappers/connection';
import { StoreOrPacketeryPoint } from 'utils/packetery/types';

type StoreSelectProps = {
    selectedStoreUuid: string;
    stores: TypeListedStoreConnectionFragment;
    onSelectStoreCallback: (newStoreUuid: string | null) => void;
};

export const StoreSelect: FC<StoreSelectProps> = ({ selectedStoreUuid, stores, onSelectStoreCallback }) => {
    const mappedStores = useMemo(() => mapConnectionEdges<StoreOrPacketeryPoint>(stores.edges), [stores.edges]);

    return (
        <ul className="max-h-[70dvh] overflow-y-auto">
            {mappedStores?.map((pickupPlace) => (
                <TransportAndPaymentListItem
                    key={pickupPlace.identifier}
                    isActive={selectedStoreUuid === pickupPlace.identifier}
                >
                    <Radiobutton
                        checked={selectedStoreUuid === pickupPlace.identifier}
                        id={pickupPlace.identifier}
                        name="selectedStore"
                        value={pickupPlace.identifier}
                        label={
                            <TransportAndPaymentSelectItemLabel
                                isSelected={selectedStoreUuid === pickupPlace.identifier}
                                name={pickupPlace.name}
                                pickupPlaceDetail={pickupPlace}
                            />
                        }
                        onChange={(event) => onSelectStoreCallback(event.target.value)}
                    />
                </TransportAndPaymentListItem>
            ))}
        </ul>
    );
};
