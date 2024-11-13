<?php

declare(strict_types=1);

namespace App\Model\Product;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Product\ProductTranslation as BaseProductTranslation;

/**
 * @ORM\Table(name="product_translations")
 * @ORM\Entity
 * @property \App\Model\Product\Product $translatable
 */
class ProductTranslation extends BaseProductTranslation
{
}
