import { SearchProducts } from './SearchProducts';
import { useSearchQuery } from './searchUtils';
import { LastVisitedProducts } from 'components/Blocks/Product/LastVisitedProducts/LastVisitedProducts';
import { SkeletonModuleProductsList } from 'components/Blocks/Skeleton/SkeletonModuleProductsList';
import { Webline } from 'components/Layout/Webline/Webline';
import { SearchContent } from 'components/Pages/Search/SearchContent';
import useTranslation from 'next-translate/useTranslation';
import Skeleton from 'react-loading-skeleton';
import { isClient } from 'utils/isClient';
import { useCurrentSearchStringQuery } from 'utils/queryParams/useCurrentSearchStringQuery';

export const SearchPageContent: FC = () => {
    const { t } = useTranslation();
    const searchString = useCurrentSearchStringQuery();
    const { searchData, isSearchFetching } = useSearchQuery(searchString);

    if (!searchString) {
        return (
            <div className="mb-5 p-12 text-center">
                <strong>{t('There are no results as you have searched with an empty query...')}</strong>
            </div>
        );
    }

    return (
        <>
            <Webline>
                {(isSearchFetching || !isClient) && (
                    <>
                        <Skeleton className="mb-5 h-11 w-1/4" />
                        <SkeletonModuleProductsList isWithoutBestsellers isWithoutDescription isWithoutNavigation />
                    </>
                )}

                {!!searchData && !isSearchFetching && <SearchContent searchResults={searchData} />}

                {!isSearchFetching && isClient && <SearchProducts />}
            </Webline>

            <LastVisitedProducts />
        </>
    );
};
