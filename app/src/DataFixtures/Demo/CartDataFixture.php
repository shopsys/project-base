<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo;

use App\Model\Cart\CartFacade;
use App\Model\Cart\Item\CartItem;
use App\Model\Customer\User\CustomerUserIdentifierFactory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Shopsys\FrameworkBundle\Component\DataFixture\AbstractReferenceFixture;

class CartDataFixture extends AbstractReferenceFixture implements DependentFixtureInterface
{
    public const CART_UUID = '1007c9a3-f570-484a-b84e-4a4f49bb35c0';

    /**
     * @var \App\Model\Cart\CartFacade
     */
    private CartFacade $cartFacade;

    /**
     * @var \App\Model\Customer\User\CustomerUserIdentifierFactory
     */
    protected CustomerUserIdentifierFactory $customerUserIdentifierFactory;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @param \App\Model\Cart\CartFacade $cartFacade
     * @param \App\Model\Customer\User\CustomerUserIdentifierFactory $customerUserIdentifierFactory
     * @param \Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct(
        CartFacade $cartFacade,
        CustomerUserIdentifierFactory $customerUserIdentifierFactory,
        EntityManagerInterface $em
    ) {
        $this->cartFacade = $cartFacade;
        $this->customerUserIdentifierFactory = $customerUserIdentifierFactory;
        $this->em = $em;
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $customerUserIdentifier = $this->customerUserIdentifierFactory->getByCartIdentifier(self::CART_UUID);
        $cart = $this->cartFacade->getCartByCustomerUserIdentifierCreateIfNotExists($customerUserIdentifier);

        /** @var \App\Model\Product\Product $product */
        $product = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1');
        $result = $this->cartFacade->addProductToExistingCart($product, 2, $cart);
        $this->updateCartItemUuid($result->getCartItem()->getId(), '5096bd50-45e1-40a6-bbe8-6192592feb56');

        /** @var \App\Model\Product\Product $product */
        $product = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '72');
        $result = $this->cartFacade->addProductToExistingCart($product, 2, $cart);
        $this->updateCartItemUuid($result->getCartItem()->getId(), 'f0d0cb7c-f873-4107-8187-f733d292b02f');
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            ProductDataFixture::class,
        ];
    }

    /**
     * @param int $id
     * @param string $uuid
     */
    private function updateCartItemUuid(int $id, string $uuid): void
    {
        $this->em
            ->createQuery(
                sprintf(
                    'UPDATE %s ci SET ci.uuid = \'%s\' WHERE ci.id = %d',
                    CartItem::class,
                    $uuid,
                    $id
                )
            )
            ->execute();
    }
}