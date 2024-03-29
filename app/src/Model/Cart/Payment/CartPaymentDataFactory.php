<?php

declare(strict_types=1);

namespace App\Model\Cart\Payment;

use App\Model\Cart\Cart;
use App\Model\Order\Preview\OrderPreviewFactory;
use App\Model\Payment\Payment;
use App\Model\Payment\PaymentFacade;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Model\Customer\User\CurrentCustomerUser;
use Shopsys\FrameworkBundle\Model\Payment\PaymentPriceCalculation;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade;

class CartPaymentDataFactory
{
    /**
     * @param \App\Model\Payment\PaymentFacade $paymentFacade
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \App\Model\Customer\User\CurrentCustomerUser $currentCustomerUser
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade $currencyFacade
     * @param \App\Model\Order\Preview\OrderPreviewFactory $orderPreviewFactory
     * @param \Shopsys\FrameworkBundle\Model\Payment\PaymentPriceCalculation $paymentPriceCalculation
     */
    public function __construct(
        private PaymentFacade $paymentFacade,
        private Domain $domain,
        private CurrentCustomerUser $currentCustomerUser,
        private CurrencyFacade $currencyFacade,
        private OrderPreviewFactory $orderPreviewFactory,
        private PaymentPriceCalculation $paymentPriceCalculation,
    ) {
    }

    /**
     * @param \App\Model\Cart\Cart $cart
     * @param string $paymentUuid
     * @param string|null $goPayBankSwift
     * @return \App\Model\Cart\Payment\CartPaymentData
     */
    public function create(Cart $cart, string $paymentUuid, ?string $goPayBankSwift): CartPaymentData
    {
        $domainId = $this->domain->getId();
        $payment = $this->paymentFacade->getEnabledOnDomainByUuid($paymentUuid, $domainId);
        $watchedPriceWithVat = $this->getPaymentWatchedPriceWithVat($domainId, $cart, $payment);

        $cartPaymentData = new CartPaymentData();
        $cartPaymentData->payment = $payment;
        $cartPaymentData->watchedPrice = $watchedPriceWithVat;
        $cartPaymentData->goPayBankSwift = $goPayBankSwift;

        return $cartPaymentData;
    }

    /**
     * @param int $domainId
     * @param \App\Model\Cart\Cart $cart
     * @param \App\Model\Payment\Payment $payment
     * @return \Shopsys\FrameworkBundle\Component\Money\Money
     */
    private function getPaymentWatchedPriceWithVat(int $domainId, Cart $cart, Payment $payment): Money
    {
        /** @var \App\Model\Customer\User\CustomerUser|null $customerUser */
        $customerUser = $this->currentCustomerUser->findCurrentCustomerUser();
        $currency = $this->currencyFacade->getDomainDefaultCurrencyByDomainId($domainId);
        $orderPreview = $this->orderPreviewFactory->create(
            $currency,
            $domainId,
            $cart->getQuantifiedProducts(),
            $cart->getTransport(),
            $payment,
            $customerUser,
            null,
            null,
            $cart->getFirstAppliedPromoCode(),
        );

        $watchedPrice = $this->paymentPriceCalculation->calculatePrice(
            $payment,
            $currency,
            $orderPreview->getProductsPrice(),
            $domainId,
        );

        return $watchedPrice->getPriceWithVat();
    }
}
