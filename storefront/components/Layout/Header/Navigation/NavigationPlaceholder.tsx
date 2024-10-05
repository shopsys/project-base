import { NavigationProps } from './Navigation';
import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { ArrowIcon } from 'components/Basic/Icon/ArrowIcon';
import { twJoin } from 'tailwind-merge';

export const NavigationPlaceholder: FC<NavigationProps> = ({ navigation, skeletonType }) => (
    <ul className="relative hidden w-full lg:flex">
        {navigation.map((navigationItem, index) => {
            const hasChildren = !!navigationItem.categoriesByColumns.length;

            return (
                <li key={index} className="group">
                    <ExtendedNextLink
                        href={navigationItem.link}
                        skeletonType={skeletonType}
                        className={twJoin(
                            'relative m-0 flex items-center p-5 group-first-of-type:pl-0 text-sm font-bold font-secondary vl:text-base',
                            'text-linkInverted no-underline',
                            'hover:text-linkInvertedHovered hover:no-underline group-hover:text-linkInvertedHovered group-hover:no-underline',
                            'active:text-linkInvertedHovered',
                            'disabled:text-linkInvertedDisabled',
                        )}
                    >
                        {navigationItem.name}
                        {hasChildren && (
                            <ArrowIcon className="ml-2 text-linkInverted group-hover:rotate-180 group-hover:text-linkInvertedHovered" />
                        )}
                    </ExtendedNextLink>
                </li>
            );
        })}
    </ul>
);
