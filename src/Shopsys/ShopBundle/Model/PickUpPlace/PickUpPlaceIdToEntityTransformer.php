<?php

namespace Shopsys\ShopBundle\Model\PickUpPlace;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PickUpPlaceIdToEntityTransformer implements DataTransformerInterface
{
    /**
     * @var \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceFacade
     */
    protected $pickUpPlaceFacade;

    /**
     * @param \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceFacade $pickUpPlaceFacade
     */
    public function __construct(PickUpPlaceFacade $pickUpPlaceFacade)
    {
        $this->pickUpPlaceFacade = $pickUpPlaceFacade;
    }

    /**
     * @var \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace
     * @param mixed $pickUpPlace
     * @return int|null
     */
    public function transform($pickUpPlace)
    {
        if ($pickUpPlace instanceof PickUpPlace) {
            return $pickUpPlace->getId();
        }
        return null;
    }

    /**
     * @var int|null
     * @param mixed $pickUpPlaceId
     * @return null|\Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace
     */
    public function reverseTransform($pickUpPlaceId)
    {
        if ($pickUpPlaceId === null) {
            return null;
        }
        try {
            $pickUpPlace = $this->pickUpPlaceFacade->getById((int)$pickUpPlaceId);
        } catch (\Shopsys\ShopBundle\Model\PickUpPlace\Exception\PickUpPlaceNotFoundException $notFoundException) {
            throw new TransformationFailedException('Pick up place not found', null, $notFoundException);
        }
        return $pickUpPlace;
    }
}
