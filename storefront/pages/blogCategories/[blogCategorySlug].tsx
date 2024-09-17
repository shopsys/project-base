import { getEndCursor } from 'components/Blocks/Product/Filter/utils/getEndCursor';
import { LastVisitedProducts } from 'components/Blocks/Product/LastVisitedProducts/LastVisitedProducts';
import { CommonLayout } from 'components/Layout/CommonLayout';
import { BlogCategoryContent } from 'components/Pages/BlogCategory/BlogCategoryContent';
import { DEFAULT_PAGE_SIZE } from 'config/constants';
import { BlogCategoriesDocument } from 'graphql/requests/blogCategories/queries/BlogCategoriesQuery.generated';
import { BlogCategoryArticlesDocument } from 'graphql/requests/blogCategories/queries/BlogCategoryArticlesQuery.generated';
import {
    useBlogCategoryQuery,
    TypeBlogCategoryQuery,
    TypeBlogCategoryQueryVariables,
    BlogCategoryQueryDocument,
} from 'graphql/requests/blogCategories/queries/BlogCategoryQuery.generated';
import { useGtmFriendlyPageViewEvent } from 'gtm/factories/useGtmFriendlyPageViewEvent';
import { useGtmPageViewEvent } from 'gtm/utils/pageViewEvents/useGtmPageViewEvent';
import { NextPage } from 'next';
import { useRouter } from 'next/router';
import { OperationResult } from 'urql';
import { createClient } from 'urql/createClient';
import { handleServerSideErrorResponseForFriendlyUrls } from 'utils/errors/handleServerSideErrorResponseForFriendlyUrls';
import { getIsRedirectedFromSsr } from 'utils/getIsRedirectedFromSsr';
import { getNumberFromUrlQuery } from 'utils/parsing/getNumberFromUrlQuery';
import { getSlugFromServerSideUrl } from 'utils/parsing/getSlugFromServerSideUrl';
import { getSlugFromUrl } from 'utils/parsing/getSlugFromUrl';
import { PAGE_QUERY_PARAMETER_NAME } from 'utils/queryParamNames';
import { useSeoTitleWithPagination } from 'utils/seo/useSeoTitleWithPagination';
import { getServerSidePropsWrapper } from 'utils/serverSide/getServerSidePropsWrapper';
import { ServerSidePropsType, initServerSideProps } from 'utils/serverSide/initServerSideProps';

const BlogCategoryPage: NextPage<ServerSidePropsType> = () => {
    const router = useRouter();
    const [{ data: blogCategoryData, fetching: isBlogCategoryFetching }] = useBlogCategoryQuery({
        variables: { urlSlug: getSlugFromUrl(router.asPath) },
    });

    const seoTitle = useSeoTitleWithPagination(
        blogCategoryData?.blogCategory?.articlesTotalCount,
        blogCategoryData?.blogCategory?.name,
        blogCategoryData?.blogCategory?.seoTitle,
    );

    const pageViewEvent = useGtmFriendlyPageViewEvent(blogCategoryData?.blogCategory);
    useGtmPageViewEvent(pageViewEvent, isBlogCategoryFetching);

    return (
        <CommonLayout
            breadcrumbs={blogCategoryData?.blogCategory?.breadcrumb}
            breadcrumbsType="blogCategory"
            description={blogCategoryData?.blogCategory?.seoMetaDescription}
            hreflangLinks={blogCategoryData?.blogCategory?.hreflangLinks}
            isFetchingData={isBlogCategoryFetching}
            title={seoTitle}
        >
            {!!blogCategoryData?.blogCategory && <BlogCategoryContent blogCategory={blogCategoryData.blogCategory} />}
            <LastVisitedProducts />
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
            const page = getNumberFromUrlQuery(context.query[PAGE_QUERY_PARAMETER_NAME], 1);

            const blogCategoryResponse: OperationResult<TypeBlogCategoryQuery, TypeBlogCategoryQueryVariables> =
                await client!
                    .query(BlogCategoryQueryDocument, {
                        urlSlug: getSlugFromServerSideUrl(context.req.url ?? ''),
                    })
                    .toPromise();

            await client!
                .query(BlogCategoryArticlesDocument, {
                    uuid: blogCategoryResponse.data?.blogCategory?.uuid,
                    endCursor: getEndCursor(page),
                    pageSize: DEFAULT_PAGE_SIZE,
                })
                .toPromise();

            const isRedirectedFromSsr = getIsRedirectedFromSsr(context.req.headers);
            if (isRedirectedFromSsr) {
                const serverSideErrorResponse = handleServerSideErrorResponseForFriendlyUrls(
                    blogCategoryResponse.error,
                    blogCategoryResponse.data?.blogCategory,
                    context.res,
                    domainConfig.url,
                );

                if (serverSideErrorResponse) {
                    return serverSideErrorResponse;
                }
            }

            const initServerSideData = await initServerSideProps({
                context,
                client,
                domainConfig,
                ssrExchange,
                prefetchedQueries: [{ query: BlogCategoriesDocument }],
            });

            return initServerSideData;
        },
);

export default BlogCategoryPage;
