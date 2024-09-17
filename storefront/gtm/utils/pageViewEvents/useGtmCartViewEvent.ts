import { useCurrentCustomerData } from 'connectors/customer/CurrentCustomer';
import { useGtmContext } from 'gtm/context/GtmProvider';
import { getGtmCartViewEvent } from 'gtm/factories/getGtmCartViewEvent';
import { GtmPageViewEventType } from 'gtm/types/events';
import { gtmSafePushEvent } from 'gtm/utils/gtmSafePushEvent';
import { useEffect, useRef } from 'react';

export const useGtmCartViewEvent = (gtmPageViewEvent: GtmPageViewEventType): void => {
    const wasViewedRef = useRef(false);
    const previousPromoCodes = useRef(JSON.stringify(gtmPageViewEvent.cart?.promoCodes));
    const { didPageViewRun, isScriptLoaded } = useGtmContext();
    const currentCustomerData = useCurrentCustomerData();

    useEffect(() => {
        if (
            isScriptLoaded &&
            didPageViewRun &&
            gtmPageViewEvent._isLoaded &&
            gtmPageViewEvent.cart !== undefined &&
            gtmPageViewEvent.cart !== null &&
            (!wasViewedRef.current || JSON.stringify(gtmPageViewEvent.cart.promoCodes) !== previousPromoCodes.current)
        ) {
            wasViewedRef.current = true;
            previousPromoCodes.current = JSON.stringify(gtmPageViewEvent.cart.promoCodes);
            gtmSafePushEvent(
                getGtmCartViewEvent(
                    gtmPageViewEvent.currencyCode,
                    gtmPageViewEvent.cart.valueWithoutVat,
                    gtmPageViewEvent.cart.valueWithVat,
                    gtmPageViewEvent.cart.products,
                    !!currentCustomerData?.arePricesHidden,
                ),
            );
        }
    }, [gtmPageViewEvent._isLoaded, gtmPageViewEvent.cart, gtmPageViewEvent.currencyCode, didPageViewRun]);
};
