import { useLoginForm, useLoginFormMeta } from './loginFormMeta';
import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { FacebookIcon } from 'components/Basic/Icon/FacebookIcon';
import { GoogleIcon } from 'components/Basic/Icon/GoogleIcon';
import { SeznamIcon } from 'components/Basic/Icon/SeznamIcon';
import { SubmitButton } from 'components/Forms/Button/SubmitButton';
import { Form, FormBlockWrapper, FormButtonWrapper, FormContentWrapper } from 'components/Forms/Form/Form';
import { FormLine } from 'components/Forms/Lib/FormLine';
import { PasswordInputControlled } from 'components/Forms/TextInput/PasswordInputControlled';
import { TextInputControlled } from 'components/Forms/TextInput/TextInputControlled';
import { useDomainConfig } from 'components/providers/DomainConfigProvider';
import { TIDs } from 'cypress/tids';
import { useSettingsQuery } from 'graphql/requests/settings/queries/SettingsQuery.generated';
import { TypeLoginTypeEnum } from 'graphql/types';
import { GtmMessageOriginType } from 'gtm/enums/GtmMessageOriginType';
import useTranslation from 'next-translate/useTranslation';
import { FormProvider, SubmitHandler } from 'react-hook-form';
import { usePersistStore } from 'store/usePersistStore';
import { LoginFormType } from 'types/form';
import { useLogin } from 'utils/auth/useLogin';
import { blurInput } from 'utils/forms/blurInput';
import { handleFormErrors } from 'utils/forms/handleFormErrors';
import { getInternationalizedStaticUrls } from 'utils/staticUrls/getInternationalizedStaticUrls';

export type LoginFormProps = {
    defaultEmail?: string;
    shouldOverwriteCustomerUserCart?: boolean;
    formContentWrapperClassName?: string;
};

export const LoginForm: FC<LoginFormProps> = ({
    defaultEmail,
    shouldOverwriteCustomerUserCart,
    formContentWrapperClassName,
}) => {
    const { t } = useTranslation();
    const cartUuid = usePersistStore((store) => store.cartUuid);
    const productListUuids: string[] = Object.values(usePersistStore((store) => store.productListUuids));
    const { url } = useDomainConfig();
    const [resetPasswordUrl] = getInternationalizedStaticUrls(['/reset-password'], url);

    const [formProviderMethods] = useLoginForm(defaultEmail);
    const formMeta = useLoginFormMeta(formProviderMethods);
    const login = useLogin();
    const [{ data: settingsData }] = useSettingsQuery();

    const getSocialNetworkLoginUrl = (
        socialNetwork: TypeLoginTypeEnum,
        cartUuid: string | null,
        shouldOverwriteCustomerUserCart: boolean | undefined,
        productListUuids: string[],
    ) => {
        let url = `/social-network/login/${socialNetwork}`;
        if (cartUuid) {
            url += `?cartUuid=${cartUuid}&shouldOverwriteCustomerUserCart=${shouldOverwriteCustomerUserCart ? 'true' : 'false'}`;
        }
        if (productListUuids.length > 0) {
            const separator = cartUuid ? '&' : '?';
            url += `${separator}productListUuids=${productListUuids.join(',')}`;
        }

        return url;
    };

    const onLoginHandler: SubmitHandler<LoginFormType> = async (data) => {
        blurInput();

        const loginResponse = await login({
            email: data.email,
            password: data.password,
            previousCartUuid: cartUuid,
            shouldOverwriteCustomerUserCart,
        });

        handleFormErrors(
            loginResponse.error,
            formProviderMethods,
            t,
            undefined,
            undefined,
            GtmMessageOriginType.login_popup,
        );
    };

    return (
        <FormProvider {...formProviderMethods}>
            <Form className="flex w-full justify-center" onSubmit={formProviderMethods.handleSubmit(onLoginHandler)}>
                <FormContentWrapper className={formContentWrapperClassName}>
                    <FormBlockWrapper>
                        <TextInputControlled
                            control={formProviderMethods.control}
                            formName={formMeta.formName}
                            name={formMeta.fields.email.name}
                            render={(textInput) => <FormLine bottomGap>{textInput}</FormLine>}
                            textInputProps={{
                                label: formMeta.fields.email.label,
                                required: true,
                                type: 'email',
                                autoComplete: 'email',
                            }}
                        />

                        <PasswordInputControlled
                            control={formProviderMethods.control}
                            formName={formMeta.formName}
                            name={formMeta.fields.password.name}
                            render={(passwordInput) => <FormLine>{passwordInput}</FormLine>}
                            passwordInputProps={{
                                label: formMeta.fields.password.label,
                                autoComplete: 'current-password',
                            }}
                        />

                        <FormButtonWrapper className="mb-5 mt-5 flex flex-col items-center justify-between gap-4 lg:mb-0 lg:border-none lg:p-0">
                            <FormButtonWrapper>
                                <SubmitButton tid={TIDs.login_form_submit_button}>{t('Log-in')}</SubmitButton>
                            </FormButtonWrapper>

                            {settingsData?.settings?.socialNetworkLoginConfig !== undefined &&
                                settingsData.settings.socialNetworkLoginConfig.length > 0 && (
                                    <div className="flex w-full justify-center gap-2 lg:order-2">
                                        {settingsData.settings.socialNetworkLoginConfig.map((socialNetwork) => (
                                            <SocialNetworkLoginLink
                                                key={socialNetwork}
                                                socialNetwork={socialNetwork}
                                                href={getSocialNetworkLoginUrl(
                                                    socialNetwork,
                                                    cartUuid,
                                                    shouldOverwriteCustomerUserCart,
                                                    productListUuids,
                                                )}
                                            />
                                        ))}
                                    </div>
                                )}

                            <div className="flex w-full items-center justify-center gap-1 whitespace-nowrap py-3 text-sm lg:order-3">
                                <ExtendedNextLink href={resetPasswordUrl}>{t('Lost your password?')}</ExtendedNextLink>
                            </div>
                        </FormButtonWrapper>
                    </FormBlockWrapper>
                </FormContentWrapper>
            </Form>
        </FormProvider>
    );
};

const SocialNetworkLoginLink: FC<{ href: string; socialNetwork: TypeLoginTypeEnum }> = ({ href, socialNetwork }) => {
    return (
        <ExtendedNextLink
            className="hover:shadow-greyLight flex h-12 w-full items-center justify-center rounded bg-background shadow-md"
            href={href}
        >
            <SocialNetworkIcon socialNetwork={socialNetwork} />
        </ExtendedNextLink>
    );
};

const SocialNetworkIcon: FC<{ socialNetwork: TypeLoginTypeEnum }> = ({ socialNetwork }) => {
    switch (socialNetwork) {
        case TypeLoginTypeEnum.Facebook:
            return <FacebookIcon className="w-7 text-[#1877f2]" />;
        case TypeLoginTypeEnum.Google:
            return <GoogleIcon className="w-6" />;
        case TypeLoginTypeEnum.Seznam:
            return <SeznamIcon className="w-6" />;
        default:
            return null;
    }
};
