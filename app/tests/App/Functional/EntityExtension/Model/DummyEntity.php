<?php

declare(strict_types=1);

namespace Tests\App\Functional\EntityExtension\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DummyEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected int $id;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, \Shopsys\FrameworkBundle\Model\Product\Flag\Flag>
     * @ORM\ManyToMany(targetEntity="Shopsys\FrameworkBundle\Model\Product\Flag\Flag")
     * @ORM\JoinTable(name="dummy_flags")
     */
    protected array|Collection $flags;
}
