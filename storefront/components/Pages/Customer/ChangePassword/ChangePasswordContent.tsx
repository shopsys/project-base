import { SubmitButton } from 'components/Forms/Button/SubmitButton';
import { Form, FormButtonWrapper, FormContentWrapper } from 'components/Forms/Form/Form';
import {
    useChangePasswordForm,
    useChangePasswordFormMeta,
} from 'components/Pages/Customer/ChangePassword/changePasswordFormMeta';
import { ChangePassword } from 'components/Pages/Customer/EditProfile/ChangePassword';
import { useChangePasswordMutation } from 'graphql/requests/customer/mutations/ChangePasswordMutation.generated';
import { GtmMessageOriginType } from 'gtm/enums/GtmMessageOriginType';
import useTranslation from 'next-translate/useTranslation';
import { FormProvider, SubmitHandler } from 'react-hook-form';
import { CurrentCustomerType } from 'types/customer';
import { ChangePasswordFormType } from 'types/form';
import { getUserFriendlyErrors } from 'utils/errors/friendlyErrorMessageParser';
import { clearForm } from 'utils/forms/clearForm';
import { showErrorMessage } from 'utils/toasts/showErrorMessage';
import { showSuccessMessage } from 'utils/toasts/showSuccessMessage';

type ChangePasswordContentProps = {
    currentCustomerUser: CurrentCustomerType;
};

export const ChangePasswordContent: FC<ChangePasswordContentProps> = ({ currentCustomerUser }) => {
    const { t } = useTranslation();
    const [, changePassword] = useChangePasswordMutation();

    const [formProviderMethods, defaultValues] = useChangePasswordForm({
        ...currentCustomerUser,
    });
    const formMeta = useChangePasswordFormMeta(formProviderMethods);
    const isSubmitting = formProviderMethods.formState.isSubmitting;

    const onSubmitChangePasswordFormHandler: SubmitHandler<ChangePasswordFormType> = async (
        changePasswordFormData,
        event,
    ) => {
        event?.preventDefault();

        onChangePasswordHandler(changePasswordFormData);
    };

    const onChangePasswordHandler = async (changePasswordFormData: ChangePasswordFormType) => {
        if (changePasswordFormData.newPassword === '' || changePasswordFormData.newPasswordConfirm === '') {
            return;
        }

        const changePasswordResult = await changePassword({
            email: currentCustomerUser.email,
            oldPassword: changePasswordFormData.oldPassword,
            newPassword: changePasswordFormData.newPassword,
        });

        if (changePasswordResult.data?.ChangePassword !== undefined) {
            showSuccessMessage(formMeta.messages.success);
            clearForm(changePasswordResult.error, formProviderMethods, defaultValues);
        }

        if (changePasswordResult.error !== undefined) {
            const { applicationError } = getUserFriendlyErrors(changePasswordResult.error, t);

            if (applicationError !== undefined) {
                if (applicationError.type === 'invalid-account-or-password') {
                    formProviderMethods.setError('oldPassword', { message: t('The current password is incorrect') });
                }
            } else {
                showErrorMessage(formMeta.messages.error, GtmMessageOriginType.other);
            }
        }
    };

    return (
        <FormProvider {...formProviderMethods}>
            <Form onSubmit={formProviderMethods.handleSubmit(onSubmitChangePasswordFormHandler)}>
                <FormContentWrapper>
                    <ChangePassword
                        email={currentCustomerUser.email}
                        hasPasswordSet={currentCustomerUser.hasPasswordSet}
                    />

                    <FormButtonWrapper className="mt-0 pb-6">
                        <SubmitButton isDisabled={isSubmitting}>{t('Change password')}</SubmitButton>
                    </FormButtonWrapper>
                </FormContentWrapper>
            </Form>
        </FormProvider>
    );
};
