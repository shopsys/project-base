<?php

namespace Shopsys\ShopBundle\Model\PickUpPlace\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PickUpPlaceNotFoundException extends NotFoundHttpException implements PickUpPlaceExceptionInterface
{
}
