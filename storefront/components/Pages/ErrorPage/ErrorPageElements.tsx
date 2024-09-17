import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { Button } from 'components/Forms/Button/Button';
import { PageType } from 'store/slices/createPageLoadingStateSlice';
import { twJoin } from 'tailwind-merge';

type ErrorPageProps = { isWithoutImage?: boolean };

export const ErrorPage: FC<ErrorPageProps> = ({ children, isWithoutImage }) => (
    <div
        className={twJoin(
            'relative my-8 mx-auto flex w-full max-w-lg flex-col flex-wrap items-center pt-5 pb-48 text-center lg:mt-12 lg:mb-20 lg:pb-0',
            isWithoutImage && 'text-center',
        )}
    >
        {children}
    </div>
);

export const ErrorPageTextHeading: FC = ({ children }) => <div className="text-2xl">{children}</div>;

export const ErrorPageTextMain: FC = ({ children }) => <div className="mt-4">{children}</div>;

export const ErrorPageButtonLink: FC<{ href: string; skeletonType?: PageType }> = ({
    href,
    children,
    skeletonType,
}) => (
    <ExtendedNextLink href={href} skeletonType={skeletonType}>
        <Button className="mt-5">{children}</Button>
    </ExtendedNextLink>
);
