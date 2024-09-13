import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { CompareIcon } from 'components/Basic/Icon/CompareIcon';
import { HeartIcon } from 'components/Basic/Icon/HeartIcon';
import { MarkerIcon } from 'components/Basic/Icon/MarkerIcon';
import { UserIcon } from 'components/Basic/Icon/UserIcon';
import { useDomainConfig } from 'components/providers/DomainConfigProvider';
import useTranslation from 'next-translate/useTranslation';
import Skeleton from 'react-loading-skeleton';
import { twJoin } from 'tailwind-merge';
import { getInternationalizedStaticUrls } from 'utils/staticUrls/getInternationalizedStaticUrls';

const placeholderItemTwClass =
    'flex flex-col items-center justify-center py-4 px-3 gap-2 rounded-tr-none text-sm text-linkInverted no-underline hover:!text-linkInvertedHovered hover:!no-underline';

export const MenuIconicPlaceholder: FC = () => {
    const { t } = useTranslation();
    const { url } = useDomainConfig();
    const [storesUrl] = getInternationalizedStaticUrls(['/stores'], url);

    return (
        <ul className="flex items-center gap-1 h-12">
            <li className="max-lg:hidden">
                <ExtendedNextLink className={placeholderItemTwClass} href={storesUrl} type="stores">
                    <MarkerIcon className="w-6" />
                    {t('Stores')}
                </ExtendedNextLink>
            </li>

            <li className={twJoin('max-lg:hidden', placeholderItemTwClass)}>
                <CompareIcon className="w-6" isFull={false} />
                {t('Comparison')}
            </li>

            <li className={twJoin('max-lg:hidden', placeholderItemTwClass)}>
                <HeartIcon className="w-6" isFull={false} />
                {t('Wishlist')}
            </li>

            <li className={placeholderItemTwClass}>
                <UserIcon className="w-6 max-h-[22px]" />
                <Skeleton className="w-10" containerClassName="max-lg:hidden" />
            </li>
        </ul>
    );
};
