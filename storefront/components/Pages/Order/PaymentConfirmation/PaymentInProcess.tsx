import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { ConfirmationPageContent } from 'components/Blocks/ConfirmationPage/ConfirmationPageContent';
import { useDomainConfig } from 'components/providers/DomainConfigProvider';
import { GtmPageType } from 'gtm/enums/GtmPageType';
import { useGtmStaticPageViewEvent } from 'gtm/factories/useGtmStaticPageViewEvent';
import { useGtmPageViewEvent } from 'gtm/utils/pageViewEvents/useGtmPageViewEvent';
import useTranslation from 'next-translate/useTranslation';
import { getInternationalizedStaticUrls } from 'utils/staticUrls/getInternationalizedStaticUrls';

type PaymentInProcessProps = {
    orderUrlHash: string;
};

export const PaymentInProcess: FC<PaymentInProcessProps> = ({ orderUrlHash }) => {
    const { t } = useTranslation();
    const { url } = useDomainConfig();
    const [orderDetailUrl] = getInternationalizedStaticUrls(
        [{ url: '/order-detail/:urlHash', param: orderUrlHash }],
        url,
    );
    const gtmStaticPageViewEvent = useGtmStaticPageViewEvent(GtmPageType.payment_in_process);
    useGtmPageViewEvent(gtmStaticPageViewEvent);

    return (
        <ConfirmationPageContent
            content={t('You can check the status on the order detail page.')}
            heading={t('The payment is being processed')}
        >
            <ExtendedNextLink href={orderDetailUrl} type="orderDetail">
                {t('Show order detail')}
            </ExtendedNextLink>
        </ConfirmationPageContent>
    );
};
