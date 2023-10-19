const TEST_IDENTIFIER = 'pages-order-transportandpayment-packeterycontainer';

export const PacketeryContainer: FC = () => (
    <div
        className="pointer-events-none absolute left-0 top-0 h-full w-full"
        data-testid={TEST_IDENTIFIER}
        id="packetery-container"
    />
);
