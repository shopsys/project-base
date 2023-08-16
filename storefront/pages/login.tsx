import { CommonLayout } from 'components/Layout/CommonLayout';
import { LoginContent } from 'components/Pages/Login/LoginContent';
import {
    BreadcrumbFragmentApi,
    CurrentCustomerUserQueryApi,
    CurrentCustomerUserQueryDocumentApi,
} from 'graphql/generated';
import { useGtmStaticPageViewEvent } from 'gtm/helpers/eventFactories';
import { getInternationalizedStaticUrls } from 'helpers/getInternationalizedStaticUrls';
import { getServerSidePropsWrapper } from 'helpers/serverSide/getServerSidePropsWrapper';
import { initServerSideProps, ServerSidePropsType } from 'helpers/serverSide/initServerSideProps';
import { createClient } from 'urql/createClient';
import { useGtmPageViewEvent } from 'gtm/hooks/useGtmPageViewEvent';
import { useTypedTranslationFunction } from 'hooks/typescript/useTypedTranslationFunction';
import { useDomainConfig } from 'hooks/useDomainConfig';
import { GtmPageType } from 'gtm/types/enums';

const LoginPage: FC<ServerSidePropsType> = () => {
    const t = useTypedTranslationFunction();
    const { url } = useDomainConfig();
    const [loginUrl] = getInternationalizedStaticUrls(['/login'], url);
    const breadcrumbs: BreadcrumbFragmentApi[] = [{ __typename: 'Link', name: t('Login'), slug: loginUrl }];
    const gtmStaticPageViewEvent = useGtmStaticPageViewEvent(GtmPageType.other, breadcrumbs);
    useGtmPageViewEvent(gtmStaticPageViewEvent);

    return (
        <CommonLayout title={t('Login')}>
            <LoginContent breadcrumbs={breadcrumbs} />
        </CommonLayout>
    );
};

export const getServerSideProps = getServerSidePropsWrapper(
    ({ redisClient, domainConfig, ssrExchange, t }) =>
        async (context) => {
            const client = createClient({
                t,
                ssrExchange,
                publicGraphqlEndpoint: domainConfig.publicGraphqlEndpoint,
                redisClient,
                context,
            });
            const serverSideProps = await initServerSideProps({
                context,
                client,
                domainConfig,
                ssrExchange,
            });

            const customerQueryResult = client.readQuery<CurrentCustomerUserQueryApi>(
                CurrentCustomerUserQueryDocumentApi,
                {},
            );
            const isLogged =
                customerQueryResult?.data?.currentCustomerUser !== undefined &&
                // eslint-disable-next-line @typescript-eslint/no-unnecessary-condition
                customerQueryResult?.data?.currentCustomerUser !== null;

            if (isLogged) {
                let redirectUrl = '/';
                if (typeof context.query.r === 'string') {
                    redirectUrl = context.query.r;
                }

                return {
                    redirect: {
                        statusCode: 302,
                        destination: redirectUrl,
                    },
                };
            }

            return serverSideProps;
        },
);

export default LoginPage;
