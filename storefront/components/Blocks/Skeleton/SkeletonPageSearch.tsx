import { SkeletonModuleProductsList } from './SkeletonModuleProductsList';
import { createEmptyArray } from 'helpers/arrayUtils';
import Skeleton from 'react-loading-skeleton';

export const SkeletonPageSearch: FC = () => (
    <div>
        <div className="mb-7 flex flex-col gap-6">
            <Skeleton className="h-full" containerClassName="h-9 w-96" />

            {createEmptyArray(2).map((_, index) => (
                <div key={index} className="w-full flex flex-col">
                    <Skeleton className="h-full" containerClassName="h-7 w-72 mb-3" />
                    <Skeleton className="h-full" containerClassName="h-16 w-full" />
                </div>
            ))}
        </div>

        <Skeleton className="h-full" containerClassName="block h-7 w-72 mb-3" />

        <SkeletonModuleProductsList isWithoutDescription isWithoutNavigation />
    </div>
);
