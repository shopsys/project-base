import { OrderDetailBasicInfo } from './OrderDetailBasicInfo';
import { OrderDetailCustomerInfo } from './OrderDetailCustomerInfo';
import { OrderPaymentStatusBar } from 'components/Pages/Customer/Orders/OrderPaymentStatusBar';
import { PaymentsInOrderSelect } from 'components/PaymentsInOrderSelect/PaymentsInOrderSelect';
import { TypeOrderDetailFragment } from 'graphql/requests/orders/fragments/OrderDetailFragment.generated';
import { PaymentTypeEnum } from 'types/payment';

type OrderDetailContentProps = {
    order: TypeOrderDetailFragment;
};

export const OrderDetailContent: FC<OrderDetailContentProps> = ({ order }) => {
    return (
        <div>
            <OrderPaymentStatusBar
                orderIsPaid={order.isPaid}
                orderIsPaymentInProcess={order.isPaymentInProcess}
                orderPaymentType={order.payment.type}
            />
            {order.payment.type === PaymentTypeEnum.GoPay && !order.isPaid && !order.isPaymentInProcess && (
                <div>
                    <PaymentsInOrderSelect
                        orderUuid={order.uuid}
                        paymentTransactionCount={order.paymentTransactionsCount}
                    />
                </div>
            )}
            <OrderDetailBasicInfo order={order} />
            <OrderDetailCustomerInfo order={order} />
        </div>
    );
};
