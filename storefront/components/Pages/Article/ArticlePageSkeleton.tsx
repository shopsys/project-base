import { Webline } from 'components/Layout/Webline/Webline';
import Skeleton from 'react-loading-skeleton';

export const ArticlePageSkeleton: FC = () => (
    <Webline>
        <Skeleton className="mb-5 h-12 w-full" />
        <Skeleton className="mb-5 h-6 w-28" />
        <Skeleton className="mb-5 h-6 w-full" />
        <Skeleton className="mb-5 h-96 w-full" />
    </Webline>
);
