import { ArrowIcon } from 'components/Basic/Icon/ArrowIcon';
import { HeartIcon } from 'components/Basic/Icon/HeartIcon';
import { Webline } from 'components/Layout/Webline/Webline';
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
        <Webline className="mt-8 lg:mt-10 vl:mt-20">
            <div className="flex items-center gap-6">
                <HeartIcon className="text-green h-20 w-20" />
                <h1 className="mb-0">
                    {t('The payment is being processed, you can check the status on the order detail page')}
                </h1>
            </div>
            <a className="w-fit" href={orderDetailUrl}>
                {t('Show order detail')}
                <ArrowIcon className="h-3 w-3 -rotate-90" />
            </a>
        </Webline>
    );
};
