<?php

namespace Shopsys\ShopBundle\Form\Admin;

use Shopsys\FrameworkBundle\Form\Admin\Transport\TransportFormType;
use Shopsys\ShopBundle\Model\Transport\Transport;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class TransportFormTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builderTransportDataGroup = $builder->get('basicInformation');
        $builderTransportDataGroup->add('type', ChoiceType::class, [
            'required' => true,
            'choices' => [
                t('Basic') => Transport::TYPE_DEFAULT,
                t('Zásilkovna') => Transport::TYPE_ZASILKOVNA,
            ],
            'constraints' => [
                new Constraints\NotBlank(['message' => 'Please check the correctness of all data filled.']),
            ],
            'label' => t('Shipping type'),
        ]);
        $builder->add($builderTransportDataGroup);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return TransportFormType::class;
    }
}
