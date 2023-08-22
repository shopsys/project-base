import { SelectedParametersList, SelectedParametersListItem, SelectedParametersName } from './FilterElements';
import { Heading } from 'components/Basic/Heading/Heading';
import { Icon } from 'components/Basic/Icon/Icon';
import { ProductFilterOptionsFragmentApi } from 'graphql/generated';
import { useFormatPrice } from 'hooks/formatting/useFormatPrice';
import useTranslation from 'next-translate/useTranslation';
import { useQueryParams } from 'hooks/useQueryParams';
import { useSessionStore } from 'store/useSessionStore';
import { getHasDefaultFilters } from 'helpers/filterOptions/seoCategories';
import { DefaultProductFiltersMapType } from 'store/slices/createSeoCategorySlice';
import { FilterOptionsParameterUrlQueryType } from 'types/productFilter';
import { Remove, RemoveThin } from 'components/Basic/Icon/IconsSvg';

const TEST_IDENTIFIER = 'blocks-product-filter-selectedparameters';

type SelectedParametersProps = {
    filterOptions: ProductFilterOptionsFragmentApi;
};

export const SelectedParameters: FC<SelectedParametersProps> = ({ filterOptions }) => {
    const { t } = useTranslation();
    const formatPrice = useFormatPrice();
    const defaultProductFiltersMap = useSessionStore((s) => s.defaultProductFiltersMap);

    const {
        filter,
        updateFilterInStock,
        updateFilterPrices,
        updateFilterBrands,
        updateFilterFlags,
        updateFilterParameters,
        resetAllFilters,
    } = useQueryParams();

    if (!filter && !getHasDefaultFilters(defaultProductFiltersMap)) {
        return null;
    }

    const checkedBrands = filter?.brands?.map((checkedBrandUuid) =>
        filterOptions.brands?.find((brandOption) => brandOption.brand.uuid === checkedBrandUuid),
    );
    const checkedFlags = getCheckedFlags(defaultProductFiltersMap, filterOptions.flags, filter?.flags);

    return (
        <div className="z-aboveOverlay rounded py-4 vl:z-[0]" data-testid={TEST_IDENTIFIER}>
            <Heading type="h4" className="uppercase">
                {t('Selected filters')}
            </Heading>
            <div className="mb-4 flex flex-col gap-3">
                {!!checkedBrands?.length && (
                    <SelectedParametersList>
                        <SelectedParametersName>{t('Brands')}:</SelectedParametersName>
                        {checkedBrands.map(
                            (checkedBrand) =>
                                !!checkedBrand && (
                                    <SelectedParametersListItem key={checkedBrand.brand.uuid}>
                                        {checkedBrand.brand.name}
                                        <SelectedParametersIcon
                                            onClick={() => updateFilterBrands(checkedBrand.brand.uuid)}
                                        />
                                    </SelectedParametersListItem>
                                ),
                        )}
                    </SelectedParametersList>
                )}

                {!!checkedFlags.length && (
                    <SelectedParametersList>
                        <SelectedParametersName>{t('Flags')}:</SelectedParametersName>
                        {checkedFlags.map((checkedFlag) => (
                            <SelectedParametersListItem key={checkedFlag.flag.uuid}>
                                {checkedFlag.flag.name}
                                <SelectedParametersIcon onClick={() => updateFilterFlags(checkedFlag.flag.uuid)} />
                            </SelectedParametersListItem>
                        ))}
                    </SelectedParametersList>
                )}

                {getSelectedParameters(defaultProductFiltersMap, filter?.parameters).map((selectedParameter) => {
                    const selectedParameterOptions = filterOptions.parameters?.find(
                        (parameterOption) => parameterOption.uuid === selectedParameter.parameter,
                    );

                    const isSliderParameter = selectedParameterOptions?.__typename === 'ParameterSliderFilterOption';
                    const isColorParameter = selectedParameterOptions?.__typename === 'ParameterColorFilterOption';
                    const isCheckBoxParameter =
                        selectedParameterOptions?.__typename === 'ParameterCheckboxFilterOption';

                    const selectedParameterValues =
                        // hack typescript because it is confused about filtering shared types
                        isCheckBoxParameter || isColorParameter
                            ? (
                                  selectedParameterOptions.values as {
                                      uuid: string;
                                      text: string;
                                      isSelected: boolean;
                                  }[]
                              ).filter((selectedParameterValue) => {
                                  return (
                                      selectedParameter.values?.includes(selectedParameterValue.uuid) ||
                                      defaultProductFiltersMap.parameters
                                          .get(selectedParameter.parameter)
                                          ?.has(selectedParameterValue.uuid)
                                  );
                              })
                            : undefined;

                    return (
                        <SelectedParametersList key={selectedParameterOptions?.uuid}>
                            <SelectedParametersName>{selectedParameterOptions?.name}:</SelectedParametersName>
                            {isSliderParameter ? (
                                <SelectedParametersListItem key={selectedParameterOptions.uuid}>
                                    {selectedParameterOptions.minimalValue && (
                                        <>
                                            <span>{t('from')}&nbsp;</span>
                                            {selectedParameterOptions.minimalValue}
                                            {selectedParameterOptions.unit?.name !== undefined
                                                ? `\xa0${selectedParameterOptions.unit.name}`
                                                : ''}
                                            {selectedParameterOptions.maximalValue && ' '}
                                        </>
                                    )}
                                    {selectedParameterOptions.maximalValue && (
                                        <>
                                            <span>{t('to')}&nbsp;</span>
                                            {selectedParameterOptions.maximalValue}
                                            {selectedParameterOptions.unit?.name !== undefined
                                                ? `\xa0${selectedParameterOptions.unit.name}`
                                                : ''}
                                        </>
                                    )}
                                    <SelectedParametersIcon
                                        onClick={() => updateFilterParameters(selectedParameterOptions.uuid, undefined)}
                                    />
                                </SelectedParametersListItem>
                            ) : (
                                selectedParameterValues?.map((selectedValue, index) => (
                                    <SelectedParametersListItem
                                        key={selectedValue.uuid}
                                        dataTestId={TEST_IDENTIFIER + index}
                                    >
                                        {selectedValue.text}
                                        <SelectedParametersIcon
                                            onClick={() =>
                                                updateFilterParameters(selectedParameter.parameter, selectedValue.uuid)
                                            }
                                            dataTestId={TEST_IDENTIFIER + 'remove-' + index}
                                        />
                                    </SelectedParametersListItem>
                                ))
                            )}
                        </SelectedParametersList>
                    );
                })}

                {!!filter?.onlyInStock && (
                    <SelectedParametersList>
                        <SelectedParametersName>{t('Availability')}:</SelectedParametersName>
                        <SelectedParametersListItem>
                            {t('Only goods in stock')}
                            <SelectedParametersIcon onClick={() => updateFilterInStock(false)} />
                        </SelectedParametersListItem>
                    </SelectedParametersList>
                )}

                {(filter?.minimalPrice !== undefined || filter?.maximalPrice !== undefined) && (
                    <SelectedParametersList>
                        <SelectedParametersName>{t('Price')}:</SelectedParametersName>
                        <SelectedParametersListItem>
                            {filter.minimalPrice !== undefined && (
                                <>
                                    <span>{t('from')}&nbsp;</span>
                                    {formatPrice(filter.minimalPrice)}
                                    {filter.maximalPrice !== undefined ? ' ' : ''}
                                </>
                            )}
                            {filter.maximalPrice !== undefined && (
                                <>
                                    <span>{t('to')}&nbsp;</span>
                                    {formatPrice(filter.maximalPrice)}
                                </>
                            )}
                            <SelectedParametersIcon
                                onClick={() => {
                                    updateFilterPrices({ maximalPrice: undefined, minimalPrice: undefined });
                                }}
                            />
                        </SelectedParametersListItem>
                    </SelectedParametersList>
                )}
            </div>
            <div className="flex cursor-pointer items-center text-sm text-greyLight" onClick={resetAllFilters}>
                <div className="font-bold uppercase">{t('Clear all')}</div>
                <Icon icon={<Remove />} className="ml-2 cursor-pointer text-greenLight" />
            </div>
        </div>
    );
};

const SelectedParametersIcon: FC<{ onClick: () => void }> = ({ onClick, dataTestId }) => (
    <Icon icon={<RemoveThin />} onClick={onClick} className="ml-3 w-3 cursor-pointer" data-testid={dataTestId} />
);

const getCheckedFlags = (
    defaultProductFiltersMap: DefaultProductFiltersMapType,
    flagFilterOptions: ProductFilterOptionsFragmentApi['flags'],
    flagsCheckedByUser: string[] = [],
) => {
    const checkedFlagsSet = new Set([...flagsCheckedByUser, ...Array.from(defaultProductFiltersMap.flags)]);

    return (flagFilterOptions ?? []).filter((flagOption) => checkedFlagsSet.has(flagOption.flag.uuid));
};

const getSelectedParameters = (
    defaultProductFiltersMap: DefaultProductFiltersMapType,
    parameters: FilterOptionsParameterUrlQueryType[] | undefined = [],
) => {
    const parametersMap = new Map(parameters.map((parameter) => [parameter.parameter, parameter]));
    const defaultProductFiltersArray = Array.from(defaultProductFiltersMap.parameters);

    for (const [defaultParameterUuid, defaultParameterSelectedValues] of defaultProductFiltersArray) {
        parametersMap.set(defaultParameterUuid, {
            parameter: defaultParameterUuid,
            values: Array.from(defaultParameterSelectedValues),
        });
    }

    return Array.from(parametersMap.values());
};
