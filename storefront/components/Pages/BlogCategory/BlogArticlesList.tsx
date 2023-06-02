import { Flag } from 'components/Basic/Flag/Flag';
import { Heading } from 'components/Basic/Heading/Heading';
import { Image } from 'components/Basic/Image/Image';
import { ListedBlogArticleFragmentApi } from 'graphql/generated';
import { getFirstImageOrNull } from 'helpers/mappers/image';
import { useDomainConfig } from 'hooks/useDomainConfig';
import NextLink from 'next/link';
import { Fragment } from 'react';

type BlogArticlesListProps = {
    blogArticles: ListedBlogArticleFragmentApi[];
};

const TEST_IDENTIFIER = 'pages-blogcategory-blogarticleslist-';

export const BlogArticlesList: FC<BlogArticlesListProps> = ({ blogArticles }) => {
    const { defaultLocale } = useDomainConfig();

    return (
        <ul className="flex w-full flex-col flex-wrap md:flex-row">
            {blogArticles.map((blogArticle, blogArticleIndex) => {
                const blogImage = getFirstImageOrNull(blogArticle.images);

                return (
                    <li
                        key={blogArticle.uuid}
                        className="mb-14 flex w-full flex-col p-0 md:flex-row"
                        data-testid={TEST_IDENTIFIER + blogArticleIndex}
                    >
                        <div
                            className="mb-3 w-full text-center md:mb-0 md:w-48"
                            data-testid={TEST_IDENTIFIER + blogArticleIndex + '-image'}
                        >
                            <NextLink href={blogArticle.link} passHref>
                                <a>
                                    <Image image={blogImage} type="list" alt={blogImage?.name || blogArticle.name} />
                                </a>
                            </NextLink>
                        </div>
                        <div className="flex w-full flex-col md:pl-10">
                            <div>
                                {blogArticle.blogCategories.map((blogArticleCategory, blogArticleCategoryIndex) => (
                                    <Fragment key={blogArticleCategory.uuid}>
                                        {blogArticleCategory.parent !== null && (
                                            <Flag
                                                href={blogArticleCategory.link}
                                                dataTestId={
                                                    TEST_IDENTIFIER +
                                                    blogArticleIndex +
                                                    '-section-' +
                                                    blogArticleCategoryIndex
                                                }
                                            >
                                                {blogArticleCategory.name}
                                            </Flag>
                                        )}
                                    </Fragment>
                                ))}
                            </div>
                            <NextLink href={blogArticle.link} passHref>
                                <a
                                    className="group hover:no-underline"
                                    data-testid={TEST_IDENTIFIER + blogArticleIndex + '-title'}
                                >
                                    <Heading type="h2" className="group-hover:text-primary">
                                        {blogArticle.name}
                                    </Heading>
                                </a>
                            </NextLink>
                            {blogArticle.perex !== null && (
                                <p
                                    className="mb-3 text-base"
                                    data-testid={TEST_IDENTIFIER + blogArticleIndex + '-perex'}
                                >
                                    {blogArticle.perex}
                                </p>
                            )}
                            <p className="text-sm font-bold" data-testid={TEST_IDENTIFIER + blogArticleIndex + '-date'}>
                                {new Date(blogArticle.publishDate).toLocaleDateString(defaultLocale)}
                            </p>
                        </div>
                    </li>
                );
            })}
        </ul>
    );
};