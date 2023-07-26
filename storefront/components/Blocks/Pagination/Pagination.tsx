import { getFilteredQueries } from 'helpers/queryParams/queryHandlers';
import { useMediaMin } from 'hooks/ui/useMediaMin';
import { usePagination } from 'hooks/ui/usePagination';
import { useQueryParams } from 'hooks/useQueryParams';
import { useRouter } from 'next/router';
import { Fragment, MouseEventHandler, RefObject, forwardRef } from 'react';
import { twJoin } from 'tailwind-merge';

type PaginationProps = {
    totalCount: number;
    paginationScrollTargetRef: RefObject<HTMLDivElement> | null;
};

const TEST_IDENTIFIER = 'blocks-pagination';

export const DEFAULT_PAGE_SIZE = 9;

export const Pagination: FC<PaginationProps> = ({ totalCount, paginationScrollTargetRef }) => {
    const router = useRouter();
    const isDesktop = useMediaMin('sm');
    const { currentPage, updatePagination } = useQueryParams();
    const paginationButtons = usePagination(totalCount, currentPage, !isDesktop, DEFAULT_PAGE_SIZE);

    if (!paginationButtons || paginationButtons.length === 1) {
        return null;
    }

    const asPathWithoutQueryParams = router.asPath.split('?')[0];
    const queryParams = getFilteredQueries(router.query);

    const onChangePage = (pageNumber: number) => () => {
        if (paginationScrollTargetRef?.current) {
            paginationScrollTargetRef.current.scrollIntoView();
        }
        updatePagination(pageNumber);
    };

    return (
        <div className="flex w-full justify-center vl:justify-end ">
            <div className="my-3 flex justify-center gap-1 vl:mr-5" data-testid={TEST_IDENTIFIER}>
                {paginationButtons.map((pageNumber, index, array) => {
                    const urlPageNumber = pageNumber > 1 ? pageNumber.toString() : undefined;
                    const pageParams = urlPageNumber
                        ? new URLSearchParams({ ...queryParams, page: urlPageNumber }).toString()
                        : undefined;
                    const pageHref = `${asPathWithoutQueryParams}${pageParams ? `?${pageParams}` : ''}`;

                    return (
                        <Fragment key={pageNumber}>
                            {isDotKey(array[index - 1] ?? null, pageNumber) && (
                                <PaginationButton isDotButton>&#8230;</PaginationButton>
                            )}
                            {currentPage === pageNumber ? (
                                <PaginationButton dataTestId={TEST_IDENTIFIER + '-' + pageNumber} isActive>
                                    {pageNumber}
                                </PaginationButton>
                            ) : (
                                <PaginationButton
                                    dataTestId={TEST_IDENTIFIER + '-' + pageNumber}
                                    onClick={onChangePage(pageNumber)}
                                    href={pageHref}
                                >
                                    {pageNumber}
                                </PaginationButton>
                            )}
                        </Fragment>
                    );
                })}
            </div>
        </div>
    );
};

const isDotKey = (prevPage: number | null, currentPage: number): boolean => {
    return prevPage !== null && prevPage !== currentPage - 1;
};

type PaginationButtonProps = {
    isActive?: boolean;
    isDotButton?: boolean;
    href?: string;
    onClick?: () => void;
};

const PaginationButton: FC<PaginationButtonProps> = forwardRef(
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    ({ children, dataTestId, isActive, isDotButton, href, onClick }, _) => {
        const handleOnClick: MouseEventHandler<HTMLAnchorElement> = (e) => {
            e.preventDefault();

            if (onClick) {
                onClick();
            }
        };

        return (
            <a
                className={twJoin(
                    'flex h-11 w-11 items-center justify-center rounded border border-white bg-white font-bold no-underline hover:no-underline',
                    isActive && 'border-none bg-orange hover:cursor-default',
                    isDotButton && 'hover:cursor-default',
                )}
                href={href}
                onClick={handleOnClick}
                data-testid={dataTestId}
            >
                {children}
            </a>
        );
    },
);

PaginationButton.displayName = 'PaginationButton';