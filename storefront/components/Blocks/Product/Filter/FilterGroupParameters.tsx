import {
    FilterGroupContent,
    FilterGroupContentItem,
    FilterGroupTitle,
    FilterGroupWrapper,
    ShowAllButton,
} from './FilterElements';
import { RangeSlider } from 'components/Basic/RangeSlider/RangeSlider';
import { Checkbox } from 'components/Forms/Checkbox/Checkbox';
import { CheckboxColor } from 'components/Forms/CheckboxColor/CheckboxColor';
import { AnimatePresence } from 'framer-motion';
import useTranslation from 'next-translate/useTranslation';
import { useState } from 'react';
import { DefaultProductFiltersMapType } from 'store/slices/createSeoCategorySlice';
import { useSessionStore } from 'store/useSessionStore';
import { ParametersType } from 'types/productFilter';
import { useCurrentFilterQuery } from 'utils/queryParams/useCurrentFilterQuery';
import { useUpdateFilterQuery } from 'utils/queryParams/useUpdateFilterQuery';

type FilterGroupParametersProps = {
    title: string;
    parameterIndex: number;
    parameter: ParametersType;
    defaultNumberOfShownParameters: number;
    isActive: boolean;
};

export const FilterGroupParameters: FC<FilterGroupParametersProps> = ({
    title,
    parameter,
    defaultNumberOfShownParameters,
    parameterIndex,
    isActive,
}) => {
    const { t } = useTranslation();
    const [isGroupCollapsed, setIsGroupCollapsed] = useState(parameter.isCollapsed);
    const currentFilter = useCurrentFilterQuery();
    const { updateFilterParametersQuery } = useUpdateFilterQuery();
    const defaultSelectedParameters = useSessionStore((s) => s.defaultProductFiltersMap.parameters);

    const selectedParameter = currentFilter?.parameters?.find((p) => p.parameter === parameter.uuid);

    const isCheckboxType = parameter.__typename === 'ParameterCheckboxFilterOption';

    // we need to check everywhere for isCheckboxType, otherwise Typescript doesn't know if .values exists
    // that's because it is sure only about overlaps within types of parameter
    const hiddenOptions = isCheckboxType
        ? parameter.values.slice(defaultNumberOfShownParameters, parameter.values.length)
        : [];

    const isWithHiddenCheckedItem = isCheckboxType
        ? hiddenOptions.some((o) => !!selectedParameter?.values?.includes(o.uuid))
        : false;

    const [isWithAllItemsShown, setIsWithAllItemsShown] = useState(isWithHiddenCheckedItem);

    const shownOptions = isCheckboxType ? parameter.values.slice(0, defaultNumberOfShownParameters) : [];
    const defaultOptions = isCheckboxType ? (isWithAllItemsShown ? parameter.values : shownOptions) : [];

    return (
        <FilterGroupWrapper>
            <FilterGroupTitle
                isActive={isActive}
                isOpen={!isGroupCollapsed}
                title={title + (parameter.unit?.name ? ` (${parameter.unit.name})` : '')}
                onClick={() => setIsGroupCollapsed(!isGroupCollapsed)}
            />

            <AnimatePresence initial={false}>
                {!isGroupCollapsed && (
                    <FilterGroupContent>
                        {isCheckboxType && (
                            <>
                                {defaultOptions.map((parameterValueOption, index) => {
                                    const isChecked = getIsSelectedParameterValue(
                                        defaultSelectedParameters,
                                        selectedParameter?.values,
                                        parameter.uuid,
                                        parameterValueOption.uuid,
                                    );
                                    const id = `parameters.${parameterIndex}.values.${index}.checked`;
                                    const isDisabled = parameterValueOption.count === 0 && !isChecked;

                                    return (
                                        <FilterGroupContentItem
                                            key={parameterValueOption.uuid}
                                            isDisabled={isDisabled}
                                            keyName={parameterValueOption.uuid}
                                        >
                                            <Checkbox
                                                count={parameterValueOption.count}
                                                disabled={isDisabled}
                                                id={id}
                                                label={parameterValueOption.text}
                                                name={id}
                                                value={isChecked}
                                                onChange={() =>
                                                    updateFilterParametersQuery(
                                                        parameter.uuid,
                                                        parameterValueOption.uuid,
                                                    )
                                                }
                                            />
                                        </FilterGroupContentItem>
                                    );
                                })}

                                {!!hiddenOptions.length && (
                                    <ShowAllButton onClick={() => setIsWithAllItemsShown((prev) => !prev)}>
                                        {isWithAllItemsShown ? t('Show less') : t('Show more')}
                                    </ShowAllButton>
                                )}
                            </>
                        )}

                        {parameter.__typename === 'ParameterColorFilterOption' && (
                            <div className="flex flex-wrap gap-1">
                                {parameter.values.map((parameterValue, index) => {
                                    const isChecked = getIsSelectedParameterValue(
                                        defaultSelectedParameters,
                                        selectedParameter?.values,
                                        parameter.uuid,
                                        parameterValue.uuid,
                                    );
                                    const id = `parameters.${parameterIndex}.values.${index}.checked`;

                                    return (
                                        <CheckboxColor
                                            key={parameterValue.uuid}
                                            bgColor={parameterValue.rgbHex ?? undefined}
                                            count={parameterValue.count}
                                            disabled={parameterValue.count === 0 && !isChecked}
                                            id={id}
                                            label={parameterValue.text}
                                            name={id}
                                            value={isChecked}
                                            onChange={() =>
                                                updateFilterParametersQuery(parameter.uuid, parameterValue.uuid)
                                            }
                                        />
                                    );
                                })}
                            </div>
                        )}
                        {parameter.__typename === 'ParameterSliderFilterOption' && (
                            <RangeSlider
                                isDisabled={!parameter.isSelectable}
                                max={parameter.maximalValue}
                                maxValue={selectedParameter?.maximalValue ?? parameter.maximalValue}
                                min={parameter.minimalValue}
                                minValue={selectedParameter?.minimalValue ?? parameter.minimalValue}
                                setMaxValueCallback={(value) =>
                                    updateFilterParametersQuery(
                                        parameter.uuid,
                                        undefined,
                                        selectedParameter?.minimalValue,
                                        parameter.maximalValue === value ? undefined : value,
                                    )
                                }
                                setMinValueCallback={(value) =>
                                    updateFilterParametersQuery(
                                        parameter.uuid,
                                        undefined,
                                        parameter.minimalValue === value ? undefined : value,
                                        selectedParameter?.maximalValue,
                                    )
                                }
                            />
                        )}
                    </FilterGroupContent>
                )}
            </AnimatePresence>
        </FilterGroupWrapper>
    );
};

const getIsSelectedParameterValue = (
    defaultSelectedParameters: DefaultProductFiltersMapType['parameters'],
    parameterValues: string[] | undefined,
    parameterUuid: string,
    parameterValueUuid: string,
) => {
    const isSelectedByDefault = !!defaultSelectedParameters.get(parameterUuid)?.has(parameterValueUuid);
    const isSelectedByUser = !!parameterValues?.includes(parameterValueUuid) || isSelectedByDefault;
    return isSelectedByDefault || isSelectedByUser;
};
