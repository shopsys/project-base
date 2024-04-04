import { TypeProductOrderingModeEnum } from 'graphql/types';
import { useRouter } from 'next/router';
import { useSessionStore } from 'store/useSessionStore';
import {
    FILTER_QUERY_PARAMETER_NAME,
    LOAD_MORE_QUERY_PARAMETER_NAME,
    PAGE_QUERY_PARAMETER_NAME,
    SORT_QUERY_PARAMETER_NAME,
} from 'utils/queryParamNames';
import { useUpdateFilterQuery } from 'utils/queryParams/useUpdateFilterQuery';
import { describe, expect, Mock, test, vi } from 'vitest';

const CATEGORY_URL = '/category-url';
const CATEGORY_PATHNAME = '/categories/[categorySlug]';
const ORIGINAL_CATEGORY_URL = '/original-category-slug';
const GET_DEFAULT_SEO_CATEGORY_PARAMETERS = () =>
    new Map([
        ['default-parameter-1', new Set(['default-parameter-value-1', 'default-parameter-value-2'])],
        ['default-parameter-2', new Set(['default-parameter-value-3', 'default-parameter-value-4'])],
    ]);
const GET_DEFAULT_SEO_CATEGORY_FLAGS = () => new Set(['default-flag-1', 'default-flag-2']);
const GET_DEFAULT_SEO_CATEGORY_BRANDS = () => new Set(['default-brands-1', 'default-brands-2']);

const mockSeoSensitiveFiltersGetter = vi.fn(() => ({
    SORT: true,
    AVAILABILITY: false,
    PRICE: false,
    FLAGS: true,
    PARAMETERS: {
        CHECKBOX: true,
        SLIDER: false,
    },
}));

vi.mock('config/constants', async (importOriginal) => {
    const actualConstantsModule = await importOriginal<any>();

    return {
        ...actualConstantsModule,
        get SEO_SENSITIVE_FILTERS() {
            return mockSeoSensitiveFiltersGetter();
        },
    };
});
const setWasRedirectedFromSeoCategoryMock = vi.fn();
const mockPush = vi.fn();
vi.mock('next/router', () => ({
    useRouter: vi.fn(() => ({
        pathname: CATEGORY_PATHNAME,
        asPath: CATEGORY_URL,
        push: mockPush,
        query: {},
    })),
}));

vi.mock('store/useSessionStore', () => ({
    useSessionStore: vi.fn((selector) => {
        return selector({
            defaultProductFiltersMap: {
                flags: new Set(),
                sort: TypeProductOrderingModeEnum.Priority,
                parameters: new Map(),
            },
            originalCategorySlug: null,
        });
    }),
}));

describe('useUpdateFilter().updateFilterPrices tests', () => {
    test('only minimalPrice should be updated if it differs from the current minimal price', () => {
        (useRouter as Mock).mockImplementation(() => ({
            pathname: CATEGORY_PATHNAME,
            asPath: CATEGORY_URL,
            push: mockPush,
            query: { [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({ minimalPrice: 100, maximalPrice: 1000 }) },
        }));

        useUpdateFilterQuery().updateFilterPricesQuery({ minimalPrice: 150, maximalPrice: 1000 });

        expect(mockPush).toBeCalledWith(
            {
                pathname: CATEGORY_PATHNAME,
                query: {
                    categorySlug: CATEGORY_URL,
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({ minimalPrice: 150, maximalPrice: 1000 }),
                },
            },
            {
                pathname: CATEGORY_URL,
                query: {
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({ minimalPrice: 150, maximalPrice: 1000 }),
                },
            },
            {
                shallow: true,
            },
        );
    });

    test('only maximalPrice should be updated if it differs from the current maximal price', () => {
        (useRouter as Mock).mockImplementation(() => ({
            pathname: CATEGORY_PATHNAME,
            asPath: CATEGORY_URL,
            push: mockPush,
            query: { [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({ minimalPrice: 100, maximalPrice: 1000 }) },
        }));

        useUpdateFilterQuery().updateFilterPricesQuery({ minimalPrice: 100, maximalPrice: 1100 });

        expect(mockPush).toBeCalledWith(
            {
                pathname: CATEGORY_PATHNAME,
                query: {
                    categorySlug: CATEGORY_URL,
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({ minimalPrice: 100, maximalPrice: 1100 }),
                },
            },
            {
                pathname: CATEGORY_URL,
                query: {
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({ minimalPrice: 100, maximalPrice: 1100 }),
                },
            },
            {
                shallow: true,
            },
        );
    });

    test('both minimalPrice and maximalPrice should be updated if they differs from the current values', () => {
        (useRouter as Mock).mockImplementation(() => ({
            pathname: CATEGORY_PATHNAME,
            asPath: CATEGORY_URL,
            push: mockPush,
            query: { [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({ minimalPrice: 100, maximalPrice: 1000 }) },
        }));

        useUpdateFilterQuery().updateFilterPricesQuery({ minimalPrice: 150, maximalPrice: 1100 });

        expect(mockPush).toBeCalledWith(
            {
                pathname: CATEGORY_PATHNAME,
                query: {
                    categorySlug: CATEGORY_URL,
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({ minimalPrice: 150, maximalPrice: 1100 }),
                },
            },
            {
                pathname: CATEGORY_URL,
                query: {
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({ minimalPrice: 150, maximalPrice: 1100 }),
                },
            },
            {
                shallow: true,
            },
        );
    });

    test('minimalPrice should be reset if it is set to undefined', () => {
        (useRouter as Mock).mockImplementation(() => ({
            pathname: CATEGORY_PATHNAME,
            asPath: CATEGORY_URL,
            push: mockPush,
            query: { [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({ minimalPrice: 100, maximalPrice: 1000 }) },
        }));

        useUpdateFilterQuery().updateFilterPricesQuery({ minimalPrice: undefined, maximalPrice: 1000 });

        expect(mockPush).toBeCalledWith(
            {
                pathname: CATEGORY_PATHNAME,
                query: {
                    categorySlug: CATEGORY_URL,
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({ maximalPrice: 1000 }),
                },
            },
            {
                pathname: CATEGORY_URL,
                query: {
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({ maximalPrice: 1000 }),
                },
            },
            {
                shallow: true,
            },
        );
    });

    test('changing price filters should not redirect from SEO category if it is not SEO-sensitive', () => {
        (useSessionStore as unknown as Mock).mockImplementation((selector) => {
            return selector({
                defaultProductFiltersMap: {
                    sort: TypeProductOrderingModeEnum.PriceAsc,
                    flags: GET_DEFAULT_SEO_CATEGORY_FLAGS(),
                    parameters: GET_DEFAULT_SEO_CATEGORY_PARAMETERS(),
                },
                originalCategorySlug: ORIGINAL_CATEGORY_URL,
            });
        });

        useUpdateFilterQuery().updateFilterPricesQuery({ minimalPrice: 100, maximalPrice: 1000 });

        expect(mockPush).toBeCalledWith(
            {
                pathname: CATEGORY_PATHNAME,
                query: {
                    categorySlug: CATEGORY_URL,
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({
                        minimalPrice: 100,
                        maximalPrice: 1000,
                    }),
                },
            },
            {
                pathname: CATEGORY_URL,
                query: {
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({
                        minimalPrice: 100,
                        maximalPrice: 1000,
                    }),
                },
            },
            {
                shallow: true,
            },
        );
    });

    test('changing price filters should redirect from SEO category if it is SEO-sensitive', () => {
        // eslint-disable-next-line @typescript-eslint/ban-ts-comment
        // @ts-ignore
        mockSeoSensitiveFiltersGetter.mockImplementation(() => ({ PRICE: true }));
        (useSessionStore as unknown as Mock).mockImplementation((selector) => {
            return selector({
                defaultProductFiltersMap: {
                    sort: TypeProductOrderingModeEnum.PriceAsc,
                    flags: GET_DEFAULT_SEO_CATEGORY_FLAGS(),
                    brands: GET_DEFAULT_SEO_CATEGORY_BRANDS(),
                    parameters: GET_DEFAULT_SEO_CATEGORY_PARAMETERS(),
                },
                originalCategorySlug: ORIGINAL_CATEGORY_URL,
                setWasRedirectedFromSeoCategory: setWasRedirectedFromSeoCategoryMock,
            });
        });

        useUpdateFilterQuery().updateFilterPricesQuery({ minimalPrice: 100, maximalPrice: 1000 });

        expect(mockPush).toBeCalledWith(
            {
                pathname: CATEGORY_PATHNAME,
                query: {
                    categorySlug: ORIGINAL_CATEGORY_URL,
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({
                        minimalPrice: 100,
                        maximalPrice: 1000,
                        brands: Array.from(GET_DEFAULT_SEO_CATEGORY_BRANDS()),
                        flags: Array.from(GET_DEFAULT_SEO_CATEGORY_FLAGS()),
                        parameters: [
                            {
                                parameter: 'default-parameter-1',
                                values: ['default-parameter-value-1', 'default-parameter-value-2'],
                            },
                            {
                                parameter: 'default-parameter-2',
                                values: ['default-parameter-value-3', 'default-parameter-value-4'],
                            },
                        ],
                    }),
                    [SORT_QUERY_PARAMETER_NAME]: TypeProductOrderingModeEnum.PriceAsc,
                },
            },
            {
                pathname: ORIGINAL_CATEGORY_URL,
                query: {
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({
                        minimalPrice: 100,
                        maximalPrice: 1000,
                        brands: Array.from(GET_DEFAULT_SEO_CATEGORY_BRANDS()),
                        flags: Array.from(GET_DEFAULT_SEO_CATEGORY_FLAGS()),
                        parameters: [
                            {
                                parameter: 'default-parameter-1',
                                values: ['default-parameter-value-1', 'default-parameter-value-2'],
                            },
                            {
                                parameter: 'default-parameter-2',
                                values: ['default-parameter-value-3', 'default-parameter-value-4'],
                            },
                        ],
                    }),
                    [SORT_QUERY_PARAMETER_NAME]: TypeProductOrderingModeEnum.PriceAsc,
                },
            },
            {
                shallow: true,
            },
        );
        expect(setWasRedirectedFromSeoCategoryMock).toBeCalledTimes(1);
        expect(setWasRedirectedFromSeoCategoryMock).toBeCalledWith(true);
    });

    test('changing prices resets page and load more', () => {
        (useRouter as Mock).mockImplementation(() => ({
            pathname: CATEGORY_PATHNAME,
            asPath: CATEGORY_URL,
            push: mockPush,
            query: {
                [PAGE_QUERY_PARAMETER_NAME]: '2',
                [LOAD_MORE_QUERY_PARAMETER_NAME]: '2',
            },
        }));

        useUpdateFilterQuery().updateFilterPricesQuery({ minimalPrice: 100, maximalPrice: 1000 });

        expect(mockPush).toBeCalledWith(
            {
                pathname: CATEGORY_PATHNAME,
                query: {
                    categorySlug: CATEGORY_URL,
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({
                        minimalPrice: 100,
                        maximalPrice: 1000,
                    }),
                },
            },
            {
                pathname: CATEGORY_URL,
                query: {
                    [FILTER_QUERY_PARAMETER_NAME]: JSON.stringify({
                        minimalPrice: 100,
                        maximalPrice: 1000,
                    }),
                },
            },
            {
                shallow: true,
            },
        );
    });
});
