import { useSessionStore } from 'store/useSessionStore';

export const useResetOriginalCategorySlug = () => {
    const originalCategorySlug = useSessionStore((s) => s.originalCategorySlug);
    const setOriginalCategorySlug = useSessionStore((s) => s.setOriginalCategorySlug);

    if (originalCategorySlug) {
        setOriginalCategorySlug(undefined);
    }
};
