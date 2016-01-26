<?php

namespace SS6\ShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SS6\ShopBundle\Component\ConfirmDelete\ConfirmDeleteResponseFactory;
use SS6\ShopBundle\Component\Controller\AdminBaseController;
use SS6\ShopBundle\Component\Router\Security\Annotation\CsrfProtection;
use SS6\ShopBundle\Form\Admin\Vat\VatSettingsFormType;
use SS6\ShopBundle\Model\Pricing\PricingSetting;
use SS6\ShopBundle\Model\Pricing\Vat\VatFacade;
use SS6\ShopBundle\Model\Pricing\Vat\VatInlineEdit;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VatController extends AdminBaseController {

	/**
	 * @var \SS6\ShopBundle\Component\ConfirmDelete\ConfirmDeleteResponseFactory
	 */
	private $confirmDeleteResponseFactory;

	/**
	 * @var \SS6\ShopBundle\Model\Pricing\PricingSetting
	 */
	private $pricingSetting;

	/**
	 * @var \SS6\ShopBundle\Model\Pricing\Vat\VatFacade
	 */
	private $vatFacade;

	/**
	 * @var \SS6\ShopBundle\Model\Pricing\Vat\VatInlineEdit
	 */
	private $vatInlineEdit;

	public function __construct(
		VatFacade $vatFacade,
		PricingSetting $pricingSetting,
		VatInlineEdit $vatInlineEdit,
		ConfirmDeleteResponseFactory $confirmDeleteResponseFactory
	) {
		$this->vatFacade = $vatFacade;
		$this->pricingSetting = $pricingSetting;
		$this->vatInlineEdit = $vatInlineEdit;
		$this->confirmDeleteResponseFactory = $confirmDeleteResponseFactory;
	}

	/**
	 * @Route("/vat/list/")
	 */
	public function listAction() {
		$grid = $this->vatInlineEdit->getGrid();

		return $this->render('@SS6Shop/Admin/Content/Vat/list.html.twig', [
			'gridView' => $grid->createView(),
		]);
	}

	/**
	 * @Route("/vat/delete-confirm/{id}", requirements={"id" = "\d+"})
	 * @param int $id
	 */
	public function deleteConfirmAction($id) {
		try {
			$vat = $this->vatFacade->getById($id);
			if ($this->vatFacade->isVatUsed($vat)) {
				$message = t(
					'Pro odstranění sazby "%name% musíte zvolit, která se má všude, '
					. 'kde je aktuálně používaná nastavit. Po změně sazby DPH dojde k přepočtu cen zboží '
					. '- základní cena s DPH zůstane zachována. Jakou sazbu místo ní chcete nastavit?',
					['%name%' => $vat->getName()]
				);
				$remainingVatsList = new ObjectChoiceList($this->vatFacade->getAllExceptId($id), 'name', [], null, 'id');

				return $this->confirmDeleteResponseFactory->createSetNewAndDeleteResponse(
					$message,
					'admin_vat_delete',
					$id,
					$remainingVatsList
				);
			} else {
				$message = t(
					'Opravdu si přejete trvale odstranit sazbu "%name%"? Nikde není použita.',
					['%name%' => $vat->getName()]
				);

				return $this->confirmDeleteResponseFactory->createDeleteResponse($message, 'admin_vat_delete', $id);
			}
		} catch (\SS6\ShopBundle\Model\Pricing\Vat\Exception\VatNotFoundException $ex) {
			return new Response(t('Zvolené DPH neexistuje'));
		}

	}

	/**
	 * @Route("/vat/delete/{id}", requirements={"id" = "\d+"})
	 * @CsrfProtection
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param int $id
	 */
	public function deleteAction(Request $request, $id) {
		$newId = $request->get('newId');

		try {
			$fullName = $this->vatFacade->getById($id)->getName();

			$this->vatFacade->deleteById($id, $newId);

			if ($newId === null) {
				$this->getFlashMessageSender()->addSuccessFlashTwig(
					t('DPH <strong>{{ name }}</strong> bylo smazáno'),
					[
						'name' => $fullName,
					]
				);
			} else {
				$newVat = $this->vatFacade->getById($newId);
				$this->getFlashMessageSender()->addSuccessFlashTwig(
					t('DPH <strong>{{ name }}</strong> bylo smazáno a bylo nahrazeno <strong>{{ newName }}</strong>.'),
					[
						'name' => $fullName,
						'newName' => $newVat->getName(),
					]);
			}

		} catch (\SS6\ShopBundle\Model\Pricing\Vat\Exception\VatNotFoundException $ex) {
			$this->getFlashMessageSender()->addErrorFlash(t('Zvolené DPH neexistuje.'));
		}

		return $this->redirectToRoute('admin_vat_list');
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 */
	public function settingsAction(Request $request) {
		$vats = $this->vatFacade->getAll();
		$form = $this->createForm(new VatSettingsFormType(
			$vats,
			PricingSetting::getRoundingTypes()
		));

		try {
			$vatSettingsFormData = [];
			$vatSettingsFormData['defaultVat'] = $this->vatFacade->getDefaultVat();
			$vatSettingsFormData['roundingType'] = $this->pricingSetting->getRoundingType();

			$form->setData($vatSettingsFormData);
			$form->handleRequest($request);

			if ($form->isValid()) {
				$vatSettingsFormData = $form->getData();

				$this->vatFacade->setDefaultVat($vatSettingsFormData['defaultVat']);
				$this->pricingSetting->setRoundingType($vatSettingsFormData['roundingType']);

				$this->getFlashMessageSender()->addSuccessFlash(t('Nastavení DPH bylo upraveno'));

				return $this->redirectToRoute('admin_vat_list');
			}
		} catch (\SS6\ShopBundle\Model\Pricing\Exception\InvalidRoundingTypeException $ex) {
			$this->getFlashMessageSender()->addErrorFlash(t('Neplatné nastavení zaokrouhlování'));
		}

		return $this->render('@SS6Shop/Admin/Content/Vat/vatSettings.html.twig', [
			'form' => $form->createView(),
		]);
	}

}
