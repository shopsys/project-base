import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { Image } from 'components/Basic/Image/Image';
import { twMergeCustom } from 'helpers/twMerge';
import { FriendlyPagesTypesKeys } from 'types/friendlyUrl';
import { ListedItemPropType } from 'types/simpleNavigation';

type SimpleNavigationListItemProps = {
    listedItem: ListedItemPropType;
    imageType?: string;
    linkType: FriendlyPagesTypesKeys | 'static';
};

export const SimpleNavigationListItem: FC<SimpleNavigationListItemProps> = ({
    listedItem,
    imageType,
    linkType,
    dataTestId,
    className,
}) => {
    const itemImage = 'mainImage' in listedItem ? listedItem.mainImage : null;

    return (
        <li data-testid={dataTestId}>
            <ExtendedNextLink
                type={linkType}
                href={`/${listedItem.slug}`}
                className={twMergeCustom(
                    'flex h-full w-full cursor-pointer flex-col items-center justify-center rounded bg-greyVeryLight px-2 py-4 no-underline transition hover:bg-whitesmoke hover:no-underline lg:flex-row lg:justify-start lg:gap-3 lg:px-3 lg:py-2',
                    className,
                )}
            >
                {itemImage && (
                    <Image
                        width={64}
                        className="h-12 min-w-[64px] mix-blend-multiply"
                        image={itemImage}
                        type={imageType ?? 'default'}
                        alt={itemImage.name || listedItem.name}
                    />
                )}

                <div className="max-w-full">
                    <span className="block max-w-full text-sm text-dark">{listedItem.name}</span>
                    {'totalCount' in listedItem && listedItem.totalCount !== undefined && (
                        <span className="ml-2 whitespace-nowrap text-sm text-greyLight">({listedItem.totalCount})</span>
                    )}
                </div>
            </ExtendedNextLink>
        </li>
    );
};
