import { useSessionStore } from 'store/useSessionStore';
import { getEmptyDefaultProductFiltersMap } from 'utils/seoCategories/getEmptyDefaultProductFiltersMap';

export const useResetSessionFilters = () => {
    const originalCategorySlug = useSessionStore((s) => s.originalCategorySlug);
    const setOriginalCategorySlug = useSessionStore((s) => s.setOriginalCategorySlug);
    const defaultProductFiltersMap = useSessionStore((s) => s.defaultProductFiltersMap);
    const setDefaultProductFiltersMap = useSessionStore((s) => s.setDefaultProductFiltersMap);

    if (
        defaultProductFiltersMap.brands.size > 0 ||
        defaultProductFiltersMap.flags.size > 0 ||
        defaultProductFiltersMap.parameters.size > 0
    ) {
        setDefaultProductFiltersMap(getEmptyDefaultProductFiltersMap());
    }

    if (originalCategorySlug) {
        setOriginalCategorySlug(undefined);
    }
};
