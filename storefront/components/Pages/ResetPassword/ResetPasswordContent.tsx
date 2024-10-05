import { usePasswordResetForm, usePasswordResetFormMeta } from './passwordResetFormMeta';
import { SubmitButton } from 'components/Forms/Button/SubmitButton';
import { Form, FormBlockWrapper, FormButtonWrapper, FormContentWrapper } from 'components/Forms/Form/Form';
import { FormLine } from 'components/Forms/Lib/FormLine';
import { TextInputControlled } from 'components/Forms/TextInput/TextInputControlled';
import { Webline } from 'components/Layout/Webline/Webline';
import { usePasswordRecoveryMutation } from 'graphql/requests/passwordRecovery/mutations/PasswordRecoveryMutation.generated';
import { GtmFormType } from 'gtm/enums/GtmFormType';
import { GtmMessageOriginType } from 'gtm/enums/GtmMessageOriginType';
import { onGtmSendFormEventHandler } from 'gtm/handlers/onGtmSendFormEventHandler';
import useTranslation from 'next-translate/useTranslation';
import { useCallback } from 'react';
import { FormProvider, SubmitHandler, useController } from 'react-hook-form';
import { PasswordResetFormType } from 'types/form';
import { blurInput } from 'utils/forms/blurInput';
import { clearForm } from 'utils/forms/clearForm';
import { handleFormErrors } from 'utils/forms/handleFormErrors';
import { useErrorPopup } from 'utils/forms/useErrorPopup';
import { showSuccessMessage } from 'utils/toasts/showSuccessMessage';

export const ResetPasswordContent: FC = () => {
    const { t } = useTranslation();
    const [, resetPassword] = usePasswordRecoveryMutation();
    const [formProviderMethods, defaultValues] = usePasswordResetForm();
    const formMeta = usePasswordResetFormMeta(formProviderMethods);

    useErrorPopup(formProviderMethods, formMeta.fields, undefined, GtmMessageOriginType.other);

    const {
        fieldState: { invalid },
        field: { value },
    } = useController({ name: formMeta.fields.email.name, control: formProviderMethods.control });

    const onResetPasswordHandler = useCallback<SubmitHandler<PasswordResetFormType>>(
        async (passwordResetFormData) => {
            blurInput();
            const resetPasswordResult = await resetPassword(passwordResetFormData);

            if (resetPasswordResult.data?.RequestPasswordRecovery !== undefined) {
                showSuccessMessage(formMeta.messages.success);
                onGtmSendFormEventHandler(GtmFormType.forgotten_password);
            }

            handleFormErrors(resetPasswordResult.error, formProviderMethods, t, formMeta.messages.error);
            clearForm(resetPasswordResult.error, formProviderMethods, defaultValues);
        },
        [formMeta.messages, formProviderMethods, resetPassword, t, defaultValues],
    );

    return (
        <Webline className="flex flex-col items-center">
            <h1 className="w-full max-w-3xl">{t('Forgotten password')}</h1>
            <FormProvider {...formProviderMethods}>
                <Form
                    className="flex w-full justify-center"
                    onSubmit={formProviderMethods.handleSubmit(onResetPasswordHandler)}
                >
                    <FormContentWrapper>
                        <FormBlockWrapper>
                            <TextInputControlled
                                control={formProviderMethods.control}
                                formName={formMeta.formName}
                                name={formMeta.fields.email.name}
                                render={(textInput) => <FormLine>{textInput}</FormLine>}
                                textInputProps={{
                                    label: formMeta.fields.email.label,
                                    required: true,
                                    type: 'email',
                                    autoComplete: 'email',
                                }}
                            />
                            <FormButtonWrapper>
                                <SubmitButton isWithDisabledLook={invalid || value.length === 0}>
                                    {t('Reset password')}
                                </SubmitButton>
                            </FormButtonWrapper>
                        </FormBlockWrapper>
                    </FormContentWrapper>
                </Form>
            </FormProvider>
        </Webline>
    );
};
