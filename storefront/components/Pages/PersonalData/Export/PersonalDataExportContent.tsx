import { usePersonalDataExportForm, usePersonalDataExportFormMeta } from './personalDataExportFormMeta';
import { UserText } from 'components/Basic/UserText/UserText';
import { SubmitButton } from 'components/Forms/Button/SubmitButton';
import { Form } from 'components/Forms/Form/Form';
import { FormLine } from 'components/Forms/Lib/FormLine';
import { TextInputControlled } from 'components/Forms/TextInput/TextInputControlled';
import { SimpleLayout } from 'components/Layout/SimpleLayout/SimpleLayout';
import { PersonalDataAccessRequestTypeEnumApi, usePersonalDataRequestMutationApi } from 'graphql/generated';
import { GtmMessageOriginType } from 'gtm/types/enums';
import { blurInput } from 'helpers/forms/blurInput';
import { clearForm } from 'helpers/forms/clearForm';
import { handleFormErrors } from 'helpers/forms/handleFormErrors';
import { showSuccessMessage } from 'helpers/toasts';
import { useErrorPopup } from 'hooks/forms/useErrorPopup';
import useTranslation from 'next-translate/useTranslation';
import { useCallback } from 'react';
import { FormProvider, SubmitHandler } from 'react-hook-form';
import { PersonalDataExportFormType } from 'types/form';

type PersonalDataExportContentProps = {
    contentSiteText: string | undefined;
};

export const PersonalDataExportContent: FC<PersonalDataExportContentProps> = ({ contentSiteText }) => {
    const { t } = useTranslation();
    const [, personalDataExport] = usePersonalDataRequestMutationApi();
    const [formProviderMethods] = usePersonalDataExportForm();
    const formMeta = usePersonalDataExportFormMeta(formProviderMethods);

    useErrorPopup(formProviderMethods, formMeta.fields, undefined, GtmMessageOriginType.other);

    const onPersonalDataExportHandler = useCallback<SubmitHandler<PersonalDataExportFormType>>(
        async (data) => {
            blurInput();
            const personalDataExportResult = await personalDataExport({
                email: data.email,
                type: PersonalDataAccessRequestTypeEnumApi.ExportApi,
            });

            if (personalDataExportResult.data?.RequestPersonalDataAccess) {
                showSuccessMessage(formMeta.messages.success);
            }

            handleFormErrors(personalDataExportResult.error, formProviderMethods, t, formMeta.messages.error);
            clearForm(personalDataExportResult.error, formProviderMethods, { email: '' });
        },
        [personalDataExport, formMeta.messages, formProviderMethods, t],
    );

    return (
        <SimpleLayout heading={t('Personal Data Export')}>
            {!!contentSiteText && (
                <div className="mb-5 block text-justify">
                    <UserText htmlContent={contentSiteText} />
                </div>
            )}
            <FormProvider {...formProviderMethods}>
                <Form onSubmit={formProviderMethods.handleSubmit(onPersonalDataExportHandler)}>
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
                    <div className="mt-8 flex w-full justify-center">
                        <SubmitButton>{t('Send')}</SubmitButton>
                    </div>
                </Form>
            </FormProvider>
        </SimpleLayout>
    );
};
