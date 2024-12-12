import { BlogSignpostItem } from './BlogSignpostItem';
import { ArrowIcon } from 'components/Basic/Icon/ArrowIcon';
import { Overlay } from 'components/Basic/Overlay/Overlay';
import { Button } from 'components/Forms/Button/Button';
import useTranslation from 'next-translate/useTranslation';
import { Fragment, useState } from 'react';
import { twJoin } from 'tailwind-merge';
import { ListedBlogCategoryRecursiveType } from 'types/blogCategory';
import { findActiveBlogCategoryPath } from 'utils/blogCategory/findActiveBlogCategoryPath';

type BlogSingpostProps = {
    activeItem: string;
    blogCategoryItems?: ListedBlogCategoryRecursiveType[];
};

export const BlogSignpost: FC<BlogSingpostProps> = ({ blogCategoryItems, activeItem }) => {
    const { t } = useTranslation();
    const [isBlogSignpostOpen, setIsBlogSignpostOpen] = useState(false);
    const activeArticleCategoryPathUuids = findActiveBlogCategoryPath(blogCategoryItems, activeItem);
    const [openUuids, setOpenUuids] = useState<string[]>(
        activeArticleCategoryPathUuids.length > 2 ? activeArticleCategoryPathUuids : [],
    );

    const handleToggle = (uuids: string[]) => setOpenUuids((prevUuids) => (prevUuids.includes(uuids[0]) ? [] : uuids));

    return (
        <>
            <div className="relative flex flex-col gap-y-2.5">
                <div className="cursor-pointer vl:cursor-text">
                    <Button
                        variant="secondary"
                        className={twJoin(
                            'relative w-full justify-between !text-base',
                            'vl:pointer-events-none  vl:bg-transparent vl:p-0 vl:font-semibold vl:text-text vl:outline-none',
                            'max-vl:z-aboveOverlay max-vl:py-2.5 max-vl:font-default',
                        )}
                        onClick={() => setIsBlogSignpostOpen(!isBlogSignpostOpen)}
                    >
                        {t('Article categories')}
                        <ArrowIcon
                            className={twJoin('size-6 transition-all vl:hidden', isBlogSignpostOpen && 'rotate-180')}
                        />
                    </Button>
                </div>

                {blogCategoryItems && (
                    <div
                        className={twJoin(
                            'flex w-full flex-col gap-y-2.5',
                            isBlogSignpostOpen
                                ? 'max-vl:absolute max-vl:top-full max-vl:z-aboveOverlay max-vl:mt-1 max-vl:rounded-2xl max-vl:bg-background max-vl:p-5'
                                : 'max-vl:hidden',
                        )}
                    >
                        {blogCategoryItems.map((blogCategory) => {
                            const isActive = activeArticleCategoryPathUuids.includes(blogCategory.uuid);

                            return (
                                <BlogSignpostItem
                                    key={blogCategory.uuid}
                                    activeArticleCategoryPathUuids={activeArticleCategoryPathUuids}
                                    activeItem={activeItem}
                                    blogCategory={blogCategory}
                                    handleToggle={handleToggle}
                                    isActive={isActive}
                                    openUuids={openUuids}
                                />
                            );
                        })}
                    </div>
                )}
            </div>
            {isBlogSignpostOpen && (
                <Overlay isHiddenOnDesktop isActive={isBlogSignpostOpen} onClick={() => setIsBlogSignpostOpen(false)} />
            )}
        </>
    );
};
