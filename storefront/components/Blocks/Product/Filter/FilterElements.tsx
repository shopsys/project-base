import { ArrowIcon } from 'components/Basic/Icon/ArrowIcon';
import { LabelLink } from 'components/Basic/LabelLink/LabelLink';
import { twJoin } from 'tailwind-merge';

export const FilterGroupWrapper: FC = ({ children }) => <div className="py-5">{children}</div>;

export const FilterGroupTitle: FC<{ isOpen: boolean; title: string; onClick: () => void; isActive: boolean }> = ({
    isOpen,
    title,
    onClick,
    isActive,
}) => (
    <div
        className="flex cursor-pointer items-center justify-between font-secondary font-semibold uppercase text-text"
        onClick={onClick}
    >
        <div className="flex items-center gap-2.5">
            {title}
            {isActive && <div className="ml- size-2 rounded-full bg-textSuccess vl:hidden" />}
        </div>
        <ArrowIcon className={twJoin('w-2.5 rotate-0 select-none text-xs transition', isOpen && 'rotate-180')} />
    </div>
);

export const FilterGroupContent: FC = ({ children }) => (
    <div className="flex flex-col flex-wrap gap-2.5 pt-2.5">{children}</div>
);

export const FilterGroupContentItem: FC<{ isDisabled: boolean }> = ({ children, isDisabled }) => (
    <div className={twJoin('', isDisabled && 'pointer-events-none opacity-30')}>{children}</div>
);

export const ShowAllButton: FC<{ onClick: () => void }> = ({ children, onClick }) => (
    <button
        className={twJoin(
            'w-fit cursor-pointer border-none bg-none p-0 text-sm underline outline-none hover:bg-none hover:no-underline',
            'text-link',
            'hover:text-linkHovered',
        )}
        onClick={onClick}
    >
        {children}
    </button>
);

export const SelectedParametersName: FC = ({ children }) => (
    <p className="font-secondary text-xs font-semibold text-inputPlaceholder">{children}</p>
);

export const SelectedParametersList: FC = ({ children }) => (
    <div className="flex flex-wrap items-center gap-x-2.5 gap-y-2">{children}</div>
);

export const SelectedParametersListItem: FC<{ onClick: () => void }> = ({ children, onClick }) => (
    <LabelLink className="group bg-backgroundAccentLess text-text last-of-type:mr-6" onClick={onClick}>
        {children}
    </LabelLink>
);
