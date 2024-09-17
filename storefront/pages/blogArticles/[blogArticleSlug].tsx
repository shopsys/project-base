import { LastVisitedProducts } from 'components/Blocks/Product/LastVisitedProducts/LastVisitedProducts';
import { CommonLayout } from 'components/Layout/CommonLayout';
import { BlogArticleDetailContent } from 'components/Pages/BlogArticle/BlogArticleDetailContent';
import {
    useBlogArticleDetailQuery,
    TypeBlogArticleDetailQuery,
    TypeBlogArticleDetailQueryVariables,
    BlogArticleDetailQueryDocument,
} from 'graphql/requests/articlesInterface/blogArticles/queries/BlogArticleDetailQuery.generated';
import { BlogCategoriesDocument } from 'graphql/requests/blogCategories/queries/BlogCategoriesQuery.generated';
import { ProductsByCatnumsDocument } from 'graphql/requests/products/queries/ProductsByCatnumsQuery.generated';
import { useGtmFriendlyPageViewEvent } from 'gtm/factories/useGtmFriendlyPageViewEvent';
import { useGtmPageViewEvent } from 'gtm/utils/pageViewEvents/useGtmPageViewEvent';
import { NextPage } from 'next';
import { useRouter } from 'next/router';
import { OgTypeEnum } from 'types/seo';
import { OperationResult } from 'urql';
import { createClient } from 'urql/createClient';
import { handleServerSideErrorResponseForFriendlyUrls } from 'utils/errors/handleServerSideErrorResponseForFriendlyUrls';
import { getIsRedirectedFromSsr } from 'utils/getIsRedirectedFromSsr';
import { getSlugFromServerSideUrl } from 'utils/parsing/getSlugFromServerSideUrl';
import { getSlugFromUrl } from 'utils/parsing/getSlugFromUrl';
import { parseCatnums } from 'utils/parsing/grapesJsParser';
import { getServerSidePropsWrapper } from 'utils/serverSide/getServerSidePropsWrapper';
import { ServerSidePropsType, initServerSideProps } from 'utils/serverSide/initServerSideProps';

const BlogArticleDetailPage: NextPage<ServerSidePropsType> = () => {
    const router = useRouter();
    const [{ data: blogArticleData, fetching: isBlogArticleFetching }] = useBlogArticleDetailQuery({
        variables: { urlSlug: getSlugFromUrl(router.asPath) },
    });

    const blogArticleImageUrl = blogArticleData?.blogArticle?.mainImage?.url;

    const pageViewEvent = useGtmFriendlyPageViewEvent(blogArticleData?.blogArticle);
    useGtmPageViewEvent(pageViewEvent, isBlogArticleFetching);

    return (
        <CommonLayout
            breadcrumbs={blogArticleData?.blogArticle?.breadcrumb}
            breadcrumbsType="blogCategory"
            canonicalQueryParams={[]}
            description={blogArticleData?.blogArticle?.seoMetaDescription}
            hreflangLinks={blogArticleData?.blogArticle?.hreflangLinks}
            isFetchingData={isBlogArticleFetching}
            ogImageUrlDefault={blogArticleImageUrl}
            ogType={OgTypeEnum.Article}
            title={blogArticleData?.blogArticle?.seoTitle || blogArticleData?.blogArticle?.name}
        >
            {!!blogArticleData?.blogArticle && <BlogArticleDetailContent blogArticle={blogArticleData.blogArticle} />}
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

            const blogArticleResponse: OperationResult<
                TypeBlogArticleDetailQuery,
                TypeBlogArticleDetailQueryVariables
            > = await client!
                .query(BlogArticleDetailQueryDocument, {
                    urlSlug: getSlugFromServerSideUrl(context.req.url ?? ''),
                })
                .toPromise();

            const parsedCatnums = parseCatnums(blogArticleResponse.data?.blogArticle?.text ?? '');

            await client!
                .query(ProductsByCatnumsDocument, {
                    catnums: parsedCatnums,
                })
                .toPromise();

            if (getIsRedirectedFromSsr(context.req.headers)) {
                const serverSideErrorResponse = handleServerSideErrorResponseForFriendlyUrls(
                    blogArticleResponse.error,
                    blogArticleResponse.data?.blogArticle,
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

export default BlogArticleDetailPage;
