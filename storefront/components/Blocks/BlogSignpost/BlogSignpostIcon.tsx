import { ArrowIcon } from 'components/Basic/Icon/IconsSvg';
import { twJoin } from 'tailwind-merge';

type BlogSignpostIconProps = { isActive: boolean };

export const BlogSignpostIcon: FC<BlogSignpostIconProps> = ({ isActive }) => (
    <ArrowIcon className={twJoin('mr-1 -rotate-90 text-creamWhite', isActive ? 'text-dark' : 'text-creamWhite')} />
);
