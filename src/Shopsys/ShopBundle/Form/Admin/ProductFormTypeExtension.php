<?php

namespace Shopsys\ShopBundle\Form\Admin;

use Shopsys\FrameworkBundle\Form\Admin\Product\ProductFormType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductFormTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $basicInformationGroup = $builder->get('basicInformationGroup');
        $basicInformationGroup->add('extId', IntegerType::class, [
            'label' =>  'External ID',
            'required' => false,
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
