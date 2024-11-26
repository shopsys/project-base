export type SelectOptionType<T = string> = {
    value: T;
    label: string;
    isDisabled?: boolean;
    count?: number | string;
};
