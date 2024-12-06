import { GtmEventType } from 'gtm/enums/GtmEventType';
import { GtmCreateWatchdogEventType } from 'gtm/types/events';
import { WatchdogFormType } from 'types/form';

export const getGtmCreateWatchDogEvent = (watchdogFormData: WatchdogFormType): GtmCreateWatchdogEventType => ({
    event: GtmEventType.create_watchdog,
    eventParameters: {
        email: watchdogFormData.email,
        productUuid: watchdogFormData.productUuid,
    },
    _clear: true,
});
