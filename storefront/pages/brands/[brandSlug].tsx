import { getEndCursor } from 'components/Blocks/Product/Filter/helpers/getEndCursor';
import { Breadcrumbs } from 'components/Layout/Breadcrumbs/Breadcrumbs';
import { CommonLayout } from 'components/Layout/CommonLayout';
import { Webline } from 'components/Layout/Webline/Webline';
import { BrandDetailContent } from 'components/Pages/BrandDetail/BrandDetailContent';
import { CategoryDetailPageSkeleton } from 'components/Pages/CategoryDetail/CategoryDetailPageSkeleton';
import {
    BrandDetailQueryApi,
    BrandDetailQueryDocumentApi,
    BrandDetailQueryVariablesApi,
    BrandProductsQueryApi,
    BrandProductsQueryDocumentApi,
    BrandProductsQueryVariablesApi,
    useBrandDetailQueryApi,
} from 'graphql/generated';
import { getMappedProductFilter } from 'helpers/filterOptions/getMappedProductFilter';
import { mapParametersFilter } from 'helpers/filterOptions/mapParametersFilter';
import { useGtmFriendlyPageViewEvent } from 'gtm/helpers/eventFactories';
import { getServerSidePropsWrapper } from 'helpers/serverSide/getServerSidePropsWrapper';
import { initServerSideProps } from 'helpers/serverSide/initServerSideProps';
import { isRedirectedFromSsr } from 'helpers/DOM/isServer';
import {
    FILTER_QUERY_PARAMETER_NAME,
    LOAD_MORE_QUERY_PARAMETER_NAME,
    PAGE_QUERY_PARAMETER_NAME,
    SORT_QUERY_PARAMETER_NAME,
} from 'helpers/queryParams/queryParamNames';
import {
    getNumberFromUrlQuery,
    getProductListSortFromUrlQuery,
    getSlugFromServerSideUrl,
    getSlugFromUrl,
} from 'helpers/parsing/urlParsing';
import { createClient } from 'urql/createClient';
import { useGtmPageViewEvent } from 'gtm/hooks/useGtmPageViewEvent';
import { NextPage } from 'next';
import { useRouter } from 'next/router';
import { useSeoTitleWithPagination } from 'hooks/seo/useSeoTitleWithPagination';
import { useQueryParams } from 'hooks/useQueryParams';
import { DEFAULT_PAGE_SIZE } from 'config/constants';
import { getRedirectWithOffsetPage } from 'helpers/pagination/loadMore';

const BrandDetailPage: NextPage = () => {
    const router = useRouter();
    const { sort, filter } = useQueryParams();
    const [{ data: brandDetailData, fetching }] = useBrandDetailQueryApi({
        variables: {
            urlSlug: getSlugFromUrl(router.asPath),
            orderingMode: sort,
            filter: mapParametersFilter(filter),
        },
    });

    const seoTitle = useSeoTitleWithPagination(
        brandDetailData?.brand?.products.totalCount,
        brandDetailData?.brand?.name,
        brandDetailData?.brand?.seoTitle,
    );

    const pageViewEvent = useGtmFriendlyPageViewEvent(brandDetailData?.brand);
    useGtmPageViewEvent(pageViewEvent, fetching);

    return (
        <CommonLayout title={seoTitle} description={brandDetailData?.brand?.seoMetaDescription}>
            {!!brandDetailData?.brand?.breadcrumb && (
                <Webline>
                    <Breadcrumbs key="breadcrumb" breadcrumb={brandDetailData.brand.breadcrumb} />
                </Webline>
            )}
            {!filter && fetching ? (
                <CategoryDetailPageSkeleton />
            ) : (
                !!brandDetailData?.brand && <BrandDetailContent brand={brandDetailData.brand} />
            )}
        </CommonLayout>
    );
};

export const getServerSideProps = getServerSidePropsWrapper(
    ({ redisClient, domainConfig, ssrExchange, t }) =>
        async (context) => {
            const urlSlug = getSlugFromServerSideUrl(context.req.url ?? '');
            const page = getNumberFromUrlQuery(context.query[PAGE_QUERY_PARAMETER_NAME], 0);
            const loadMore = getNumberFromUrlQuery(context.query[LOAD_MORE_QUERY_PARAMETER_NAME], 1);
            const redirect = getRedirectWithOffsetPage(page, loadMore, urlSlug, context.query);

            if (redirect) {
                return redirect;
            }

            const client = createClient({
                t,
                ssrExchange,
                publicGraphqlEndpoint: domainConfig.publicGraphqlEndpoint,
                redisClient,
                context,
            });

            if (isRedirectedFromSsr(context.req.headers)) {
                const orderingMode = getProductListSortFromUrlQuery(context.query[SORT_QUERY_PARAMETER_NAME]);
                const filter = getMappedProductFilter(context.query[FILTER_QUERY_PARAMETER_NAME]);

                const brandDetailResponsePromise = client!
                    .query<BrandDetailQueryApi, BrandDetailQueryVariablesApi>(BrandDetailQueryDocumentApi, {
                        urlSlug,
                        orderingMode,
                        filter,
                    })
                    .toPromise();

                const brandProductsResponsePromise = client!
                    .query<BrandProductsQueryApi, BrandProductsQueryVariablesApi>(BrandProductsQueryDocumentApi, {
                        endCursor: getEndCursor(page),
                        orderingMode,
                        filter,
                        urlSlug,
                        pageSize: DEFAULT_PAGE_SIZE * (loadMore + 1),
                    })
                    .toPromise();

                const [brandDetailResponse] = await Promise.all([
                    brandDetailResponsePromise,
                    brandProductsResponsePromise,
                ]);

                if (
                    (!brandDetailResponse.data || !brandDetailResponse.data.brand) &&
                    !(context.res.statusCode === 503)
                ) {
                    return {
                        notFound: true,
                    };
                }
            }

            const initServerSideData = await initServerSideProps({
                context,
                client,
                ssrExchange,
                domainConfig,
            });

            return initServerSideData;
        },
);

export default BrandDetailPage;
