<?php

namespace Shopsys\ShopBundle\Form\Admin;

use Shopsys\FrameworkBundle\Form\Admin\Product\ProductFormType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class ProductFormTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $basicInformationGroup = $builder->get('basicInformationGroup');
        $basicInformationGroup->add('extId', IntegerType::class, [
            'required' => true,
            'constraints' => [
                new Constraints\NotBlank(['message' => 'Please enter external ID']),
            ],
            'label' => 'External ID',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ProductFormType::class;
    }
}
