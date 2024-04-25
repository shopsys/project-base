import { yupResolver } from '@hookform/resolvers/yup';
import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { WarningIcon } from 'components/Basic/Icon/IconsSvg';
import { Link } from 'components/Basic/Link/Link';
import { SubmitButton } from 'components/Forms/Button/SubmitButton';
import { Form } from 'components/Forms/Form/Form';
import { FormLine } from 'components/Forms/Lib/FormLine';
import { PasswordInputControlled } from 'components/Forms/TextInput/PasswordInputControlled';
import { TextInputControlled } from 'components/Forms/TextInput/TextInputControlled';
import { Popup } from 'components/Layout/Popup/Popup';
import { useDomainConfig } from 'components/providers/DomainConfigProvider';
import { GtmMessageOriginType } from 'gtm/types/enums';
import { blurInput } from 'helpers/forms/blurInput';
import { handleFormErrors } from 'helpers/forms/handleFormErrors';
import { getInternationalizedStaticUrls } from 'helpers/getInternationalizedStaticUrls';
import { useAuth } from 'hooks/auth/useAuth';
import { useShopsysForm } from 'hooks/forms/useShopsysForm';
import { Translate } from 'next-translate';
import useTranslation from 'next-translate/useTranslation';
import { FormProvider, SubmitHandler } from 'react-hook-form';
import { usePersistStore } from 'store/usePersistStore';
import * as Yup from 'yup';

type LoginPopupProps = {
    defaultEmail?: string;
    shouldOverwriteCustomerUserCart?: boolean;
};

export const LoginPopup: FC<LoginPopupProps> = ({ defaultEmail, shouldOverwriteCustomerUserCart }) => {
    const { t } = useTranslation();
    const cartUuid = usePersistStore((store) => store.cartUuid);
    const { url } = useDomainConfig();
    const [resetPasswordUrl, registrationUrl] = getInternationalizedStaticUrls(
        ['/reset-password', '/registration'],
        url,
    );
    const formProviderMethods = useShopsysForm(getLoginFormResolver(t), { email: defaultEmail ?? '', password: '' });
    const { login } = useAuth();

    const onLoginHandler: SubmitHandler<{ email: string; password: string }> = async (data) => {
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
        <Popup>
            <div className="h2 mb-3">{t('Login')}</div>
            <div className="flex w-full max-w-xs flex-col sm:max-w-md md:max-w-2xl lg:max-w-3xl lg:flex-row">
                <div className="w-full border-b border-primary lg:w-1/2 lg:border-b-0 lg:border-r lg:pr-5">
                    <FormProvider {...formProviderMethods}>
                        <Form onSubmit={formProviderMethods.handleSubmit(onLoginHandler)}>
                            <TextInputControlled
                                control={formProviderMethods.control}
                                formName="login-form"
                                name="email"
                                render={(textInput) => <FormLine bottomGap>{textInput}</FormLine>}
                                textInputProps={{
                                    label: t('Your email'),
                                    required: true,
                                    type: 'email',
                                    autoComplete: 'email',
                                }}
                            />

                            <PasswordInputControlled
                                control={formProviderMethods.control}
                                formName="login-form"
                                name="password"
                                render={(passwordInput) => <FormLine>{passwordInput}</FormLine>}
                                passwordInputProps={{
                                    label: t('Password'),
                                }}
                            />

                            <div className="mt-5 mb-5 flex items-center justify-between gap-2 lg:mb-0 lg:block lg:border-none lg:p-0">
                                <div className="order-1 flex w-full justify-end">
                                    <SubmitButton className="max-lg:!px-3">{t('Log-in')}</SubmitButton>
                                </div>

                                <div className="flex items-center gap-1 whitespace-nowrap rounded border-primary py-2 px-2 text-sm text-primary lg:mt-5 lg:border-2 lg:px-3 lg:py-3">
                                    <WarningIcon className=" h-5 w-9 text-red" />
                                    <ExtendedNextLink href={resetPasswordUrl}>
                                        <div className="block text-sm text-primary underline hover:no-underline">
                                            {t('Lost your password?')}
                                        </div>
                                    </ExtendedNextLink>
                                </div>
                            </div>
                        </Form>
                    </FormProvider>
                </div>

                <div className="mt-7 w-full lg:mt-0 lg:w-1/2 lg:pl-5">
                    <div className="mb-6 -mr-4 flex w-full justify-between rounded-l bg-blueLight p-4">
                        <p className="text-lg text-primary lg:text-xl">{t("Don't have an account yet? Register.")}</p>
                    </div>

                    <p className="mb-8 hidden lg:block">
                        {t('Your addresses prefilled and you can check your order history.')}
                    </p>

                    <Link isButton href={registrationUrl}>
                        {t('Register')}
                    </Link>
                </div>
            </div>
        </Popup>
    );
};

const getLoginFormResolver = (t: Translate) => {
    return yupResolver(
        Yup.object().shape<Record<keyof { email: string; password: string }, any>>({
            email: Yup.string().required(t('This field is required')).email(t('This value is not a valid email')),
            password: Yup.string().required(t('This field is required')),
        }),
    );
};
