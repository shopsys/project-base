<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Form\Admin;

use Shopsys\FrameworkBundle\Form\Admin\Administrator\AdministratorFormType;
use Shopsys\ShopBundle\Model\Administrator\AdministratorRolesFacade;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class AdministratorFormTypeExtension extends AbstractTypeExtension
{
    /**
     * @var \Shopsys\ShopBundle\Model\Administrator\AdministratorRolesFacade
     */
    private $administratorRolesFacade;

    /**
     * @param \Shopsys\ShopBundle\Model\Administrator\AdministratorRolesFacade $administratorRolesFacade
     */
    public function __construct(AdministratorRolesFacade $administratorRolesFacade)
    {
        $this->administratorRolesFacade = $administratorRolesFacade;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builderSettingsGroup = $builder->get('settings');

        $builderSettingsGroup
            ->add('roles', ChoiceType::class, [
                'label' => t('Administrator has rights to'),
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'choices' => $this->administratorRolesFacade->getAllRolesIndexedByTitles(),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return AdministratorFormType::class;
    }
}
