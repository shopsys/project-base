import { Icon } from 'components/Basic/Icon/Icon';
import { Arrow } from 'components/Basic/Icon/IconsSvg';
import { twJoin } from 'tailwind-merge';

type BlogSignpostIconProps = { isActive: boolean };

export const BlogSignpostIcon: FC<BlogSignpostIconProps> = ({ isActive }) => (
    <Icon
        icon={<Arrow />}
        className={twJoin('mr-1 -rotate-90 text-creamWhite', isActive ? 'text-dark' : 'text-creamWhite')}
    />
);
