import { useLogoutMutation } from 'graphql/requests/auth/mutations/LogoutMutation.generated';
import { removeTokensFromCookies } from 'helpers/auth/removeTokensFromCookies';
import { dispatchBroadcastChannel } from 'hooks/useBroadcastChannel';
import { useRouter } from 'next/router';
import { usePersistStore } from 'store/usePersistStore';
import { useSessionStore } from 'store/useSessionStore';

type LogoutHandler = () => Promise<void>;

export const useLogout = () => {
    const [, TypeLogoutMutation] = useLogoutMutation();

    const updateAuthLoadingState = usePersistStore((store) => store.updateAuthLoadingState);
    const updatePageLoadingState = useSessionStore((s) => s.updatePageLoadingState);
    const updateProductListUuids = usePersistStore((s) => s.updateProductListUuids);

    const router = useRouter();

    const logout: LogoutHandler = async () => {
        const logoutResult = await TypeLogoutMutation({});

        if (logoutResult.data?.Logout) {
            updateProductListUuids({});
            removeTokensFromCookies();
            updatePageLoadingState({ isPageLoading: true, redirectPageType: 'homepage' });
            updateAuthLoadingState('logout-loading');

            router.reload();
        }

        dispatchBroadcastChannel('reloadPage');
    };

    return logout;
};