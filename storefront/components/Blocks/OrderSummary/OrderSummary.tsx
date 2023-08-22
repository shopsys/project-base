import { ProductsPreview } from './ProductsPreview';
import { PromoCode } from './PromoCode';
import { TotalPrice } from './TotalPrice';
import { TransportAndPayment } from './TransportAndPayment';
import { LoaderWithOverlay } from 'components/Basic/Loader/LoaderWithOverlay';
import { Adverts } from 'components/Blocks/Adverts/Adverts';
import { useCurrentCart } from 'connectors/cart/Cart';
import useTranslation from 'next-translate/useTranslation';

type OrderSummaryProps = {
    isTransportOrPaymentLoading?: boolean;
};

const TEST_IDENTIFIER = 'blocks-ordersummary';

export const OrderSummary: FC<OrderSummaryProps> = ({ isTransportOrPaymentLoading }) => {
    const { t } = useTranslation();
    const { cart, transport, payment, promoCode } = useCurrentCart();

    if (cart === null) {
        return null;
    }

    return (
        <>
            <Adverts positionName="cartPreview" withGapBottom />
            <div className="w-full vl:w-full vl:max-w-md" data-testid={TEST_IDENTIFIER}>
                <h3 className="mb-3 font-bold lg:text-lg">{t('Your order')}</h3>
                <div className="-mx-5 rounded bg-greyVeryLight py-3 px-5 vl:m-0">
                    <div className="relative flex flex-col">
                        <ProductsPreview cartItems={cart.items} />
                        <div className="relative">
                            {isTransportOrPaymentLoading && (transport || payment) && (
                                <LoaderWithOverlay className="w-8" />
                            )}
                            {(transport || payment) && <TransportAndPayment transport={transport} payment={payment} />}
                            {promoCode && <PromoCode promoCode={promoCode} discount={cart.totalDiscountPrice} />}
                        </div>
                        <TotalPrice totalPrice={cart.totalPrice} />
                    </div>
                </div>
            </div>
        </>
    );
};
