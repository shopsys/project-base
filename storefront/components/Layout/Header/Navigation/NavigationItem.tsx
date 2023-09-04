import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { ArrowIcon } from 'components/Basic/Icon/IconsSvg';
import { NavigationItemColumn } from 'components/Layout/Header/Navigation/NavigationItemColumn';
import { CategoriesByColumnFragmentApi } from 'graphql/generated';
import { useState } from 'react';
import { twJoin } from 'tailwind-merge';

type NavigationItemProps = {
    navigationItem: CategoriesByColumnFragmentApi;
};

const TEST_IDENTIFIER = 'layout-header-navigation-navigationitem';

export const NavigationItem: FC<NavigationItemProps> = (props) => {
    const [isMenuOpened, setIsMenuOpened] = useState(false);
    const hasChildren = props.navigationItem.categoriesByColumns.length > 0;

    return (
        <li
            className="group"
            data-testid={TEST_IDENTIFIER}
            onMouseEnter={() => setIsMenuOpened(true)}
            onMouseLeave={() => setIsMenuOpened(false)}
        >
            <ExtendedNextLink
                type="category"
                href={props.navigationItem.link}
                className={twJoin(
                    'relative m-0 flex items-center px-2 py-4 text-sm font-bold uppercase text-white no-underline hover:text-orangeLight hover:no-underline group-hover:text-orangeLight group-hover:no-underline vl:text-base',
                )}
            >
                <>
                    {props.navigationItem.name}
                    {hasChildren && (
                        <ArrowIcon className="ml-2 text-white group-hover:rotate-180 group-hover:text-orangeLight" />
                    )}
                </>
            </ExtendedNextLink>

            {hasChildren && isMenuOpened && (
                <div className="absolute left-0 right-0 z-menu grid grid-cols-4 gap-11 bg-white py-12 px-10 shadow-md">
                    <NavigationItemColumn columnCategories={props.navigationItem.categoriesByColumns} />
                </div>
            )}
        </li>
    );
};
