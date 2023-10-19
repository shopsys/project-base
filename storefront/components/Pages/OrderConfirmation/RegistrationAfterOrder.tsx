import { useRegistrationAfterOrderForm, useRegistrationAfterOrderFormMeta } from './registrationAfterOrderFormMeta';
import { Heading } from 'components/Basic/Heading/Heading';
import { SubmitButton } from 'components/Forms/Button/SubmitButton';
import { CheckboxControlled } from 'components/Forms/Checkbox/CheckboxControlled';
import { Form } from 'components/Forms/Form/Form';
import { ChoiceFormLine } from 'components/Forms/Lib/ChoiceFormLine';
import { FormLine } from 'components/Forms/Lib/FormLine';
import { PasswordInputControlled } from 'components/Forms/TextInput/PasswordInputControlled';
import { Webline } from 'components/Layout/Webline/Webline';
import { useRegistrationMutationApi } from 'graphql/generated';
import { onGtmSendFormEventHandler } from 'gtm/helpers/eventHandlers';
import { GtmFormType, GtmMessageOriginType } from 'gtm/types/enums';
import { setTokensToCookies } from 'helpers/auth/tokens';
import { getUserFriendlyErrors } from 'helpers/errors/friendlyErrorMessageParser';
import { showErrorMessage, showSuccessMessage } from 'helpers/toasts';
import { useErrorPopupVisibility } from 'hooks/forms/useErrorPopupVisibility';
import Trans from 'next-translate/Trans';
import useTranslation from 'next-translate/useTranslation';
import dynamic from 'next/dynamic';
import { FormProvider, SubmitHandler } from 'react-hook-form';
import { ContactInformation } from 'store/slices/createContactInformationSlice';
import { RegistrationAfterOrderFormType } from 'types/form';

const ErrorPopup = dynamic(() => import('components/Forms/Lib/ErrorPopup').then((component) => component.ErrorPopup));

const TEST_IDENTIFIER = 'pages-orderconfirmation-registration-create-account';

type RegistrationAfterOrderProps = {
    lastOrderUuid: string;
    registrationData: ContactInformation;
};

export const RegistrationAfterOrder: FC<RegistrationAfterOrderProps> = ({ lastOrderUuid, registrationData }) => {
    const [, register] = useRegistrationMutationApi();
    const { t } = useTranslation();
    const [formProviderMethods] = useRegistrationAfterOrderForm();
    const formMeta = useRegistrationAfterOrderFormMeta(formProviderMethods);
    const [isErrorPopupVisible, setErrorPopupVisibility] = useErrorPopupVisibility(formProviderMethods);

    const onRegistrationSubmitHandler: SubmitHandler<RegistrationAfterOrderFormType> = async (data) => {
        const registerResult = await register({
            ...data,
            ...registrationData,
            country: registrationData.country.value,
            companyCustomer: registrationData.customer === 'companyCustomer',
            previousCartUuid: null,
            lastOrderUuid,
        });

        if (registerResult.data !== undefined && registerResult.error === undefined) {
            const accessToken = registerResult.data.Register.tokens.accessToken;
            const refreshToken = registerResult.data.Register.tokens.refreshToken;

            setTokensToCookies(accessToken, refreshToken);
            showSuccessMessage(t('Your account has been created and you are logged in now'));
            onGtmSendFormEventHandler(GtmFormType.registration);

            window.location.href = '/';
        } else if (registerResult.error !== undefined) {
            const validationErrors = getUserFriendlyErrors(registerResult.error, t).userError?.validation;
            for (const fieldName in validationErrors) {
                showErrorMessage(validationErrors[fieldName].message, GtmMessageOriginType.order_confirmation_page);
            }
        }
    };

    return (
        <>
            <Webline>
                <div className="relative mb-20 flex flex-col rounded border-2 border-primary before:absolute before:bottom-0 before:left-1/2 before:top-0 before:hidden before:w-1 before:-translate-x-1/2 before:bg-primary before:content-none lg:flex-row before:lg:block">
                    <div className="w-full p-5 lg:w-1/2 lg:py-8 lg:px-10">
                        <Heading className="mb-5 text-4xl leading-10 [&>strong]:text-primary" type="h2">
                            <Trans
                                components={{ 0: <br />, 1: <strong /> }}
                                i18nKey="Finish registration to loyalty program."
                            />
                        </Heading>
                        <ul>
                            {[
                                t('You will have an overview of your orders and complaints'),
                                t('Collecting points with every order'),
                                t('Possibility of purchases for better prices'),
                                t('Exclusive products as a part of the loyalty program'),
                            ].map((text) => (
                                <li
                                    key={text}
                                    className="relative mb-3 pl-4 leading-5 before:absolute before:left-0 before:top-2 before:h-1 before:w-1 before:rounded-full before:bg-primary before:content-none"
                                >
                                    {text}
                                </li>
                            ))}
                        </ul>
                    </div>
                    <div className="flex w-full flex-col items-center justify-center p-5 lg:w-1/2 lg:px-10 lg:py-8">
                        <div className="w-full lg:max-w-sm">
                            <Form onSubmit={formProviderMethods.handleSubmit(onRegistrationSubmitHandler)}>
                                <FormProvider {...formProviderMethods}>
                                    <PasswordInputControlled
                                        control={formProviderMethods.control}
                                        formName={formMeta.formName}
                                        name={formMeta.fields.password.name}
                                        passwordInputProps={{
                                            label: formMeta.fields.password.label,
                                        }}
                                        render={(passwordInput) => (
                                            <div className="mb-7">
                                                <FormLine>{passwordInput}</FormLine>
                                            </div>
                                        )}
                                    />
                                    <CheckboxControlled
                                        control={formProviderMethods.control}
                                        formName={formMeta.formName}
                                        name={formMeta.fields.privacyPolicy.name}
                                        checkboxProps={{
                                            label: formMeta.fields.privacyPolicy.label,
                                        }}
                                        render={(checkbox) => (
                                            <div className="mb-7">
                                                <ChoiceFormLine>{checkbox}</ChoiceFormLine>
                                            </div>
                                        )}
                                    />
                                    <SubmitButton
                                        dataTestId={TEST_IDENTIFIER}
                                        isWithDisabledLook={!formProviderMethods.formState.isValid}
                                        style={{ width: '100%' }}
                                        variant="primary"
                                    >
                                        {t('Create account')}
                                    </SubmitButton>
                                </FormProvider>
                            </Form>
                        </div>
                    </div>
                </div>
            </Webline>
            {isErrorPopupVisible && (
                <ErrorPopup
                    fields={formMeta.fields}
                    gtmMessageOrigin={GtmMessageOriginType.order_confirmation_page}
                    onCloseCallback={() => setErrorPopupVisibility(false)}
                />
            )}
        </>
    );
};
