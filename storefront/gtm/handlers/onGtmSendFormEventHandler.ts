import { GtmFormType } from 'gtm/enums/GtmFormType';
import { getGtmSendFormEvent } from 'gtm/factories/getGtmSendFormEvent';
import { gtmSafePushEvent } from 'gtm/helpers/gtmSafePushEvent';

export const onGtmSendFormEventHandler = (form: GtmFormType): void => {
    gtmSafePushEvent(getGtmSendFormEvent(form));
};