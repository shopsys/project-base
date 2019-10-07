<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Customer;

use Doctrine\ORM\EntityManagerInterface;

class DeliveryAddressRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getUserRepository()
    {
        return $this->em->getRepository(DeliveryAddress::class);
    }

    /**
     * @param int $id
     * @return \Shopsys\ShopBundle\Model\Customer\DeliveryAddress|null
     */
    public function getById(int $id)
    {
        return $this->getUserRepository()->find($id);
    }
}
