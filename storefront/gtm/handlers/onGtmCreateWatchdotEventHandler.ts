import { getGtmCreateWatchDogEvent } from 'gtm/factories/getGtmCreateWatchDogEvent';
import { gtmSafePushEvent } from 'gtm/utils/gtmSafePushEvent';
import { WatchdogFormType } from 'types/form';

export const onGtmCreateWatchdotEventHandler = (watchdogFormData: WatchdogFormType): void => {
    gtmSafePushEvent(getGtmCreateWatchDogEvent(watchdogFormData));
};
