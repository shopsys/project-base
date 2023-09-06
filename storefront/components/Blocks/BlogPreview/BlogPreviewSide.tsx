import { Flag } from 'components/Basic/Flag/Flag';
import { Image } from 'components/Basic/Image/Image';
import { ListedBlogArticleFragmentApi } from 'graphql/generated';
import { Fragment } from 'react';
import { ArticleLink } from './BlogPreviewElements';

type SideProps = {
    articles: ListedBlogArticleFragmentApi[];
};

export const BlogPreviewSide: FC<SideProps> = ({ articles }) => (
    <div className="grid snap-x snap-mandatory auto-cols-[80%] gap-4 overflow-y-hidden overscroll-x-contain max-vl:grid-flow-col max-vl:overflow-x-auto md:auto-cols-[40%] lg:gap-6 vl:flex vl:basis-1/3 vl:flex-col vl:gap-3">
        {articles.map((article) => (
            <div className="flex flex-1 snap-start flex-col gap-2 vl:flex-row" key={article.uuid}>
                <ArticleLink href={article.link} className="vl:basis-32">
                    <Image
                        image={article.mainImage}
                        type="list"
                        alt={article.mainImage?.name || article.name}
                        className="rounded"
                    />
                </ArticleLink>

                <div className="flex flex-1 flex-col items-start gap-2">
                    {article.blogCategories.map((blogPreviewCategorie) => (
                        <Fragment key={blogPreviewCategorie.uuid}>
                            {blogPreviewCategorie.parent && (
                                <Flag href={blogPreviewCategorie.link}>{blogPreviewCategorie.name}</Flag>
                            )}
                        </Fragment>
                    ))}

                    <ArticleLink
                        href={article.link}
                        className="block text-lg font-bold leading-5 text-creamWhite no-underline hover:text-creamWhite"
                    >
                        {article.name}
                    </ArticleLink>
                </div>
            </div>
        ))}
    </div>
);
