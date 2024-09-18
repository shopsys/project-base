import { FilterGroupGeneric } from './FilterGroupGeneric';
import { FilterGroupInStock } from './FilterGroupInStock';
import { FilterGroupParameters } from './FilterGroupParameters';
import { FilterGroupPrice } from './FilterGroupPrice';
import { FilterSelectedParameters } from './FilterSelectedParameters';
import { RemoveIcon } from 'components/Basic/Icon/RemoveIcon';
import { Button } from 'components/Forms/Button/Button';
import { TypeProductFilterOptionsFragment } from 'graphql/requests/productFilterOptions/fragments/ProductFilterOptionsFragment.generated';
import { TypeProductOrderingModeEnum } from 'graphql/types';
import useTranslation from 'next-translate/useTranslation';
import { ParametersType } from 'types/productFilter';
import { isPriceVisible } from 'utils/mappers/price';

export type FilterPanelProps = {
    productFilterOptions: TypeProductFilterOptionsFragment;
    defaultOrderingMode?: TypeProductOrderingModeEnum | null;
    orderingMode: TypeProductOrderingModeEnum | null;
    originalSlug: string | null;
    slug: string;
    panelCloseHandler?: () => void;
    totalCount: number;
};

const DEFAULT_NUMBER_OF_SHOWN_FLAGS = 5;
const DEFAULT_NUMBER_OF_SHOWN_BRANDS = 5;
const DEFAULT_NUMBER_OF_SHOWN_PARAMETERS = 5;

export const FilterPanel: FC<FilterPanelProps> = ({
    productFilterOptions: filterOptions,
    panelCloseHandler,
    totalCount,
}) => {
    const { t } = useTranslation();

    return (
        <div className="z-aboveOverlay flex h-full flex-col bg-backgroundMore pb-1 vl:z-above vl:h-auto vl:rounded">
            <div className="border-b-2 border-borderAccent px-5 vl:border-none">
                <div className="flex items-center justify-between bg-backgroundMore py-5 text-2xl vl:hidden">
                    {t('Product filter')}
                    <span
                        className="relative inline-flex h-7 w-7 cursor-pointer items-center justify-center rounded-full"
                        onClick={panelCloseHandler}
                    >
                        <RemoveIcon className="w-6 text-textAccent" />
                    </span>
                </div>

                <FilterSelectedParameters filterOptions={filterOptions} />
            </div>

            <div className="h-full divide-y divide-borderAccent overflow-y-scroll px-5 vl:static vl:overflow-visible">
                {isPriceVisible(filterOptions.minimalPrice) && (
                    <FilterGroupPrice
                        initialMaxPrice={filterOptions.maximalPrice}
                        initialMinPrice={filterOptions.minimalPrice}
                        title={t('Price')}
                    />
                )}

                {!!filterOptions.inStock && (
                    <FilterGroupInStock inStockCount={filterOptions.inStock} title={t('Availability')} />
                )}

                {!!filterOptions.flags?.length && (
                    <FilterGroupGeneric
                        defaultNumberOfShownItems={DEFAULT_NUMBER_OF_SHOWN_FLAGS}
                        filterField="flags"
                        options={filterOptions.flags.map(({ flag, ...rest }) => ({ ...flag, ...rest }))}
                        title={t('Flags')}
                    />
                )}

                {!!filterOptions.brands?.length && (
                    <FilterGroupGeneric
                        defaultNumberOfShownItems={DEFAULT_NUMBER_OF_SHOWN_BRANDS}
                        filterField="brands"
                        options={filterOptions.brands.map(({ brand, ...rest }) => ({ ...brand, ...rest }))}
                        title={t('Brands')}
                    />
                )}

                {filterOptions.parameters?.map((parameter, index) => (
                    <FilterGroupParameters
                        key={parameter.uuid}
                        defaultNumberOfShownParameters={DEFAULT_NUMBER_OF_SHOWN_PARAMETERS}
                        parameter={parameter as ParametersType}
                        parameterIndex={index}
                        title={parameter.name}
                    />
                ))}
            </div>

            <div className="flex items-center justify-end border-t-2 border-borderAccent p-5 vl:hidden">
                <Button
                    className="inline-block lowercase first-letter:uppercase"
                    size="small"
                    onClick={panelCloseHandler}
                >
                    {t('Show')}
                    {` ${totalCount} `}
                    {t('Products count', {
                        count: totalCount,
                    })}
                </Button>
            </div>
        </div>
    );
};
