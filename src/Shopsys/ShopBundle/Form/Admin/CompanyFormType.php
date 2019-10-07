<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Form\Admin;

use Shopsys\ShopBundle\Form\Front\Customer\BillingAddressFormType;
use Shopsys\ShopBundle\Form\Front\Customer\DeliveryAddressFormType;
use Shopsys\ShopBundle\Form\Front\Customer\UserFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyFormType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('billingAddress', BillingAddressFormType::class, [
                'domain_id' => $options['domain_id'],
                'render_form_row' => false,
                'attr' => [
                    'class' => 'wrap-divider',
                ],
            ])
            ->add('users', CollectionType::class, [
                'entry_type' => UserFormType::class,
            ])
            ->add('deliveryAddresses', CollectionType::class, [
                'entry_type' => DeliveryAddressFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [
                    'domain_id' => $options['domain_id'],
                ],
            ])
            ->add('save', SubmitType::class);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['domain_id'])
            ->setAllowedTypes('domain_id', 'int')
            ->setDefaults([
                'attr' => ['novalidate' => 'novalidate'],
            ]);
    }
}
