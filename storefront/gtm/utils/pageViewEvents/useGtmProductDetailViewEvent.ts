import { useDomainConfig } from 'components/providers/DomainConfigProvider';
import { useCurrentCustomerData } from 'connectors/customer/CurrentCustomer';
import { TypeMainVariantDetailFragment } from 'graphql/requests/products/fragments/MainVariantDetailFragment.generated';
import { TypeProductDetailFragment } from 'graphql/requests/products/fragments/ProductDetailFragment.generated';
import { useGtmContext } from 'gtm/context/GtmProvider';
import { getGtmProductDetailViewEvent } from 'gtm/factories/getGtmProductDetailViewEvent';
import { gtmSafePushEvent } from 'gtm/utils/gtmSafePushEvent';
import { useEffect, useRef } from 'react';

export const useGtmProductDetailViewEvent = (
    productDetailData: TypeProductDetailFragment | TypeMainVariantDetailFragment,
    slug: string,
    isProductFetching: boolean,
): void => {
    const lastViewedProductDetailSlug = useRef<string | undefined>(undefined);
    const { url, currencyCode } = useDomainConfig();
    const { didPageViewRun, isScriptLoaded } = useGtmContext();
    const currentCustomerData = useCurrentCustomerData();

    useEffect(() => {
        if (isScriptLoaded && didPageViewRun && lastViewedProductDetailSlug.current !== slug && !isProductFetching) {
            lastViewedProductDetailSlug.current = slug;
            gtmSafePushEvent(
                getGtmProductDetailViewEvent(
                    productDetailData,
                    currencyCode,
                    url,
                    !!currentCustomerData?.arePricesHidden,
                ),
            );
        }
    }, [productDetailData, currencyCode, slug, url, isProductFetching, didPageViewRun]);
};
