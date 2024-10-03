import { ArrowIcon } from 'components/Basic/Icon/ArrowIcon';
import { twMergeCustom } from 'utils/twMerge';

type BlogSignpostIconProps = { isActive: boolean };

export const BlogSignpostIcon: FC<BlogSignpostIconProps> = ({ isActive }) => (
    <ArrowIcon
        className={twMergeCustom('size-4 -rotate-90 text-textSubtle', isActive ? 'text-textInverted' : 'text-text')}
    />
);
