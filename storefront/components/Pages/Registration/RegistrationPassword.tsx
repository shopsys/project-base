import { FormHeading, FormBlockWrapper } from 'components/Forms/Form/Form';
import { FormColumn } from 'components/Forms/Lib/FormColumn';
import { FormLine } from 'components/Forms/Lib/FormLine';
import { PasswordInputControlled } from 'components/Forms/TextInput/PasswordInputControlled';
import { useRegistrationFormMeta } from 'components/Pages/Registration/registrationFormMeta';
import useTranslation from 'next-translate/useTranslation';
import { useFormContext } from 'react-hook-form';
import { RegistrationFormType } from 'types/form';

export const RegistrationPassword: FC = () => {
    const { t } = useTranslation();
    const formProviderMethods = useFormContext<RegistrationFormType>();
    const formMeta = useRegistrationFormMeta(formProviderMethods);

    return (
        <FormBlockWrapper>
            <FormHeading>{t('Password')}</FormHeading>
            <FormColumn className="gap-3">
                <PasswordInputControlled
                    control={formProviderMethods.control}
                    formName={formMeta.formName}
                    name={formMeta.fields.password.name}
                    render={(passwordInput) => <FormLine>{passwordInput}</FormLine>}
                    passwordInputProps={{
                        label: formMeta.fields.password.label,
                        autoComplete: 'new-password',
                    }}
                />
                <PasswordInputControlled
                    control={formProviderMethods.control}
                    formName={formMeta.formName}
                    name={formMeta.fields.passwordConfirm.name}
                    render={(passwordInput) => <FormLine>{passwordInput}</FormLine>}
                    passwordInputProps={{
                        label: formMeta.fields.passwordConfirm.label,
                        autoComplete: 'new-password-confirm',
                    }}
                />
            </FormColumn>
        </FormBlockWrapper>
    );
};
