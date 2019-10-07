<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade;
use Shopsys\FrameworkBundle\Component\Grid\GridFactory;
use Shopsys\FrameworkBundle\Component\Grid\QueryBuilderDataSource;
use Shopsys\FrameworkBundle\Component\Router\DomainRouterFactory;
use Shopsys\FrameworkBundle\Controller\Admin\CustomerController as BaseCustomerController;
use Shopsys\FrameworkBundle\Form\Admin\QuickSearch\QuickSearchFormData;
use Shopsys\FrameworkBundle\Form\Admin\QuickSearch\QuickSearchFormType;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorGridFacade;
use Shopsys\FrameworkBundle\Model\AdminNavigation\BreadcrumbOverrider;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactory;
use Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerFacade;
use Shopsys\FrameworkBundle\Model\Customer\CustomerListAdminFacade;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactory;
use Shopsys\FrameworkBundle\Model\Customer\UserDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Order\OrderFacade;
use Shopsys\FrameworkBundle\Model\Security\LoginAsUserFacade;
use Shopsys\ShopBundle\Form\Admin\CompanyFormType;
use Shopsys\ShopBundle\Model\Company\CompanyDataFactory;
use Shopsys\ShopBundle\Model\Company\CompanyFacade;
use Symfony\Component\HttpFoundation\Request;

/**
 * @property \Shopsys\ShopBundle\Model\Customer\UserDataFactory $userDataFactory
 * @method string getSsoLoginAsUserUrl(\Shopsys\ShopBundle\Model\Customer\User $user)
 */
class CustomerController extends BaseCustomerController
{
    /**
     * @var \Shopsys\ShopBundle\Model\Company\CompanyFacade
     */
    private $companyFacade;

    /**
     * @var \Shopsys\ShopBundle\Model\Company\CompanyDataFactory
     */
    private $companyDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactory
     */
    private $billingAddressDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactory
     */
    private $deliveryAddressDataFactory;

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\UserDataFactory $userDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerListAdminFacade $customerListAdminFacade
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerFacade $customerFacade
     * @param \Shopsys\FrameworkBundle\Model\AdminNavigation\BreadcrumbOverrider $breadcrumbOverrider
     * @param \Shopsys\FrameworkBundle\Model\Administrator\AdministratorGridFacade $administratorGridFacade
     * @param \Shopsys\FrameworkBundle\Component\Grid\GridFactory $gridFactory
     * @param \Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade $adminDomainTabsFacade
     * @param \Shopsys\FrameworkBundle\Model\Order\OrderFacade $orderFacade
     * @param \Shopsys\FrameworkBundle\Model\Security\LoginAsUserFacade $loginAsUserFacade
     * @param \Shopsys\FrameworkBundle\Component\Router\DomainRouterFactory $domainRouterFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactoryInterface $customerDataFactory
     * @param \Shopsys\ShopBundle\Model\Company\CompanyFacade $companyFacade
     * @param \Shopsys\ShopBundle\Model\Company\CompanyDataFactory $companyDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactory $billingAddressDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactory $deliveryAddressDataFactory
     */
    public function __construct(
        UserDataFactoryInterface $userDataFactory,
        CustomerListAdminFacade $customerListAdminFacade,
        CustomerFacade $customerFacade,
        BreadcrumbOverrider $breadcrumbOverrider,
        AdministratorGridFacade $administratorGridFacade,
        GridFactory $gridFactory,
        AdminDomainTabsFacade $adminDomainTabsFacade,
        OrderFacade $orderFacade,
        LoginAsUserFacade $loginAsUserFacade,
        DomainRouterFactory $domainRouterFactory,
        CustomerDataFactoryInterface $customerDataFactory,
        CompanyFacade $companyFacade,
        CompanyDataFactory $companyDataFactory,
        BillingAddressDataFactory $billingAddressDataFactory,
        DeliveryAddressDataFactory $deliveryAddressDataFactory
    ) {
        parent::__construct($userDataFactory, $customerListAdminFacade, $customerFacade, $breadcrumbOverrider, $administratorGridFacade, $gridFactory, $adminDomainTabsFacade, $orderFacade, $loginAsUserFacade, $domainRouterFactory, $customerDataFactory);
        $this->companyFacade = $companyFacade;
        $this->companyDataFactory = $companyDataFactory;
        $this->billingAddressDataFactory = $billingAddressDataFactory;
        $this->deliveryAddressDataFactory = $deliveryAddressDataFactory;
    }

    /**
     * @Route("/customer/list/")
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function listAction(Request $request)
    {
        $administrator = $this->getUser();
        /* @var $administrator \Shopsys\ShopBundle\Model\Administrator\Administrator */

        $quickSearchForm = $this->createForm(QuickSearchFormType::class, new QuickSearchFormData());
        $quickSearchForm->handleRequest($request);

        $queryBuilder = $this->companyFacade->getCompanyListQueryBuilderByQuickSearchData(
            $this->adminDomainTabsFacade->getSelectedDomainId(),
            $quickSearchForm->getData()
        );

        $dataSource = new QueryBuilderDataSource($queryBuilder, 'c.id');

        $grid = $this->gridFactory->create('customerList', $dataSource);
        $grid->enablePaging();
        $grid->setDefaultOrder('ba.companyName');

        $grid->addColumn('name', 'companyName', t('Full name'), true);
        $grid->addColumn('userCount', 'userCount', t('User count'), false);
        $grid->addColumn('deliveryAddressCount', 'deliveryAddressCount', t('Delivery address count'), false);

        $grid->setActionColumnClassAttribute('table-col table-col-10');
        $grid->addEditActionColumn('admin_customer_edit', ['id' => 'id']);
        $grid->addDeleteActionColumn('admin_customer_delete', ['id' => 'id'])
            ->setConfirmMessage(t('Do you really want to remove this company?'));

        $grid->setTheme('@ShopsysShop/Admin/Content/Customer/listGrid.html.twig');

        $this->administratorGridFacade->restoreAndRememberGridLimit($administrator, $grid);

        return $this->render('@ShopsysShop/Admin/Content/Customer/list.html.twig', [
            'gridView' => $grid->createView(),
            'quickSearchForm' => $quickSearchForm->createView(),
        ]);
    }

    /**
     * @Route("/customer/edit/{id}", requirements={"id" = "\d+"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     */
    public function editAction(Request $request, $id)
    {
        $company = $this->companyFacade->getById((int)$id);

        $companyData = $this->companyDataFactory->create();
        $companyData->billingAddress = $this->billingAddressDataFactory->createFromBillingAddress($company->getBillingAddress());

        foreach ($company->getUsers() as $user) {
            $companyData->users[$user->getId()] = $this->userDataFactory->createFromUser($user);
        }

        foreach ($company->getDeliveryAddresses() as $deliveryAddress) {
            $companyData->deliveryAddresses[$deliveryAddress->getId()] = $this->deliveryAddressDataFactory->createFromDeliveryAddress($deliveryAddress);
        }

        $form = $this->createForm(CompanyFormType::class, $companyData, [
            'domain_id' => 1,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->companyFacade->edit($form->getData(), $company);
        }

        return $this->render('@ShopsysShop/Admin/Content/Customer/edit.html.twig', [
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }
}
