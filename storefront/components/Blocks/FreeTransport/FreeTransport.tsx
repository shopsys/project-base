import Trans from 'next-translate/Trans';
import useTranslation from 'next-translate/useTranslation';
import { useCurrentCart } from 'utils/cart/useCurrentCart';
import { useFormatPrice } from 'utils/formatting/useFormatPrice';
import { isPriceVisible } from 'utils/mappers/price';

export const FreeTransport: FC = () => {
    const { cart } = useCurrentCart();
    const { t } = useTranslation();
    const formatPrice = useFormatPrice();
    const remainingAmount = cart?.remainingAmountWithVatForFreeTransport;

    if (
        !cart?.items.length ||
        remainingAmount === null ||
        remainingAmount === undefined ||
        !isPriceVisible(remainingAmount)
    ) {
        return null;
    }

    const remainingAmountFormatted = formatPrice(remainingAmount);

    if (parseInt(remainingAmount) > 0) {
        return (
            <RemainingAmountWrapper>
                <Trans
                    i18nKey="FreeTransportAmountLeft"
                    values={{ remainingAmountFormatted }}
                    components={{
                        0: <strong />,
                    }}
                />
            </RemainingAmountWrapper>
        );
    }

    return (
        <RemainingAmountWrapper>
            <strong>{t('Your delivery and payment is now free of charge!')}</strong>
        </RemainingAmountWrapper>
    );
};

const RemainingAmountWrapper: FC = ({ children }) => (
    <div className="my-2 block rounded bg-backgroundAccentLess text-textAccent px-3 py-1 text-xs [&_strong]:font-bold">
        {children}
    </div>
);
