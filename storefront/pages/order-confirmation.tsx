import { MetaRobots } from 'components/Basic/Head/MetaRobots';
import { ConfirmationPageContent } from 'components/Blocks/ConfirmationPage/ConfirmationPageContent';
import { CommonLayout } from 'components/Layout/CommonLayout';
import { Webline } from 'components/Layout/Webline/Webline';
import { GoPayGateway } from 'components/Pages/Order/PaymentConfirmation/Gateways/GoPayGateway';
import { RegistrationAfterOrder } from 'components/Pages/OrderConfirmation/RegistrationAfterOrder';
import { TIDs } from 'cypress/tids';
import {
    useOrderSentPageContentQuery,
    TypeOrderSentPageContentQueryVariables,
    OrderSentPageContentQueryDocument,
} from 'graphql/requests/orders/queries/OrderSentPageContentQuery.generated';
import { GtmPageType } from 'gtm/enums/GtmPageType';
import { useGtmStaticPageViewEvent } from 'gtm/factories/useGtmStaticPageViewEvent';
import { useGtmPageViewEvent } from 'gtm/utils/pageViewEvents/useGtmPageViewEvent';
import useTranslation from 'next-translate/useTranslation';
import { useRouter } from 'next/router';
import { useEffect } from 'react';
import { PaymentTypeEnum } from 'types/payment';
import { useCurrentCart } from 'utils/cart/useCurrentCart';
import { getServerSidePropsWrapper } from 'utils/serverSide/getServerSidePropsWrapper';
import { initServerSideProps, ServerSidePropsType } from 'utils/serverSide/initServerSideProps';
import { getInternationalizedStaticUrls } from 'utils/staticUrls/getInternationalizedStaticUrls';

export type OrderConfirmationUrlQuery = {
    orderUuid: string | undefined;
    orderEmail: string | undefined;
    orderPaymentType: string | undefined;
    orderUrlHash?: string | undefined;
    orderPaymentStatusPageValidityHash: string | undefined;
};

const OrderConfirmationPage: FC<ServerSidePropsType> = () => {
    const { t } = useTranslation();
    const { query } = useRouter();
    const { fetchCart } = useCurrentCart(false);
    const { orderUuid, orderPaymentType } = query as OrderConfirmationUrlQuery;

    const gtmStaticPageViewEvent = useGtmStaticPageViewEvent(GtmPageType.order_confirmation);
    useGtmPageViewEvent(gtmStaticPageViewEvent);

    const [{ data: orderSentPageContentData, fetching: isOrderSentPageContentFetching }] = useOrderSentPageContentQuery(
        {
            variables: { orderUuid: orderUuid! },
        },
    );

    useEffect(() => {
        fetchCart();
    }, []);

    return (
        <>
            <MetaRobots content="noindex" />

            <CommonLayout
                isFetchingData={isOrderSentPageContentFetching}
                pageTypeOverride="order-confirmation"
                title={t('Thank you for your order')}
            >
                <Webline tid={TIDs.pages_orderconfirmation}>
                    <ConfirmationPageContent
                        content={orderSentPageContentData?.orderSentPageContent}
                        heading={t('Your order was created')}
                        AdditionalContent={
                            orderPaymentType === PaymentTypeEnum.GoPay ? (
                                <GoPayGateway orderUuid={orderUuid!} />
                            ) : undefined
                        }
                    />
                    <RegistrationAfterOrder />
                </Webline>
            </CommonLayout>
        </>
    );
};

export const getServerSideProps = getServerSidePropsWrapper(({ redisClient, domainConfig, t }) => async (context) => {
    const { orderUuid, orderEmail } = context.query as OrderConfirmationUrlQuery;

    if (!orderUuid || !orderEmail) {
        return {
            redirect: {
                destination: getInternationalizedStaticUrls(['/cart'], domainConfig.url)[0],
                statusCode: 301,
            },
        };
    }

    return initServerSideProps<TypeOrderSentPageContentQueryVariables>({
        context,
        prefetchedQueries: [
            {
                query: OrderSentPageContentQueryDocument,
                variables: { orderUuid },
            },
        ],
        redisClient,
        domainConfig,
        t,
    });
});

export default OrderConfirmationPage;
