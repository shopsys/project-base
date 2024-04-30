import { twMergeCustom } from 'helpers/twMerge';
import Skeleton from 'react-loading-skeleton';

type SkeletonModuleProductListItemProps = {
    isSimpleCard?: boolean;
};

export const SkeletonModuleProductListItem: FC<SkeletonModuleProductListItemProps> = ({ isSimpleCard }) => (
    <div className="p-3">
        <Skeleton
            className="h-full rounded-none lg:mb-24 w-full sm:w-2/3"
            containerClassName={twMergeCustom([
                'h-[180px] mb-2 lg:h-40 w-full flex justify-center',
                isSimpleCard ? 'lg:mb-20' : 'lg:mb-24',
            ])}
        />
        <Skeleton className="mb-6 h-6 w-5/6 lg:mb-4 lg:h-4" containerClassName="w-2/3 lg:w-full" />
        <Skeleton className="h-6 w-16 lg:h-4" containerClassName="w-1/3 lg:w-full" />
        {!isSimpleCard && (
            <Skeleton className="mt-2 mb-10 h-3 w-4/6 lg:mb-16 lg:h-5" containerClassName="w-1/2 lg:w-full" />
        )}
        <Skeleton className="h-12 w-full" />
    </div>
);
