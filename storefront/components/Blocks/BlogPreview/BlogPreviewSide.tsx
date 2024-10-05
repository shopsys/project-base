import { ArticleLink } from './BlogPreviewElements';
import { Flag } from 'components/Basic/Flag/Flag';
import { Image } from 'components/Basic/Image/Image';
import { TIDs } from 'cypress/tids';
import { TypeListedBlogArticleFragment } from 'graphql/requests/articlesInterface/blogArticles/fragments/ListedBlogArticleFragment.generated';
import { Fragment } from 'react';

type SideProps = {
    articles: TypeListedBlogArticleFragment[];
};

export const BlogPreviewSide: FC<SideProps> = ({ articles }) => (
    <div className="grid snap-x snap-mandatory auto-cols-[40%] gap-4 overflow-y-hidden overscroll-x-contain max-vl:grid-flow-col max-vl:overflow-x-auto lg:gap-6 vl:flex vl:basis-1/3 vl:flex-col vl:gap-3">
        {articles.map((article) => (
            <ArticleLink
                key={article.uuid}
                className="flex flex-1 snap-start flex-col gap-2 bg-backgroundMore p-4 no-underline transition-colors hover:bg-backgroundMost hover:no-underline vl:flex-row"
                href={article.link}
            >
                <Image
                    alt={article.mainImage?.name || article.name}
                    className="mx-auto h-20 w-auto rounded"
                    height={250}
                    sizes="(max-width: 600px) 90vw, (max-width: 1024px) 40vw, 10vw"
                    src={article.mainImage?.url}
                    tid={TIDs.blog_preview_image}
                    width={768}
                />

                <div className="flex flex-1 flex-col items-start gap-2">
                    {article.blogCategories.map((blogPreviewCategorie) => (
                        <Fragment key={blogPreviewCategorie.uuid}>
                            {blogPreviewCategorie.parent && (
                                <Flag href={blogPreviewCategorie.link} type="blog">
                                    {blogPreviewCategorie.name}
                                </Flag>
                            )}
                        </Fragment>
                    ))}

                    {article.name}
                </div>
            </ArticleLink>
        ))}
    </div>
);
