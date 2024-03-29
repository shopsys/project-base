import { useEffect } from 'react';
import { useCookiesStore } from 'store/useCookiesStore';

const LAST_VISITED_MAX_ITEMS = 10;

export const useLastVisitedProductView = (visitedProduct: string) => {
    const lastVisitedProductsCatnums = useCookiesStore((state) => state.lastVisitedProductsCatnums);
    const setCookiesStoreState = useCookiesStore((state) => state.setCookiesStoreState);

    useEffect(() => {
        const newLastVisitedProductsCatnums = Array.from(
            new Set([visitedProduct, ...(lastVisitedProductsCatnums || [])]),
        );

        setCookiesStoreState({
            lastVisitedProductsCatnums: newLastVisitedProductsCatnums.slice(0, LAST_VISITED_MAX_ITEMS),
        });
    }, []);
};
