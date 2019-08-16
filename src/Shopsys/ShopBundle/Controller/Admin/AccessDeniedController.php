<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Shopsys\FrameworkBundle\Controller\Admin\AdminBaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessDeniedController extends AdminBaseController
{
    /**
     * @Route("/access-denied/")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accessDeniedAction(Request $request): Response
    {
        $this->getFlashMessageSender()->addErrorFlashTwig(
            t('You don\'t hava the rights to access this page. If you need to access it please contact your administrator.')
        );
        $referer = $request->headers->get('referer');
        $urlToRedirect = $referer === null ? $this->generateUrl('admin_default_dashboard') : $referer;
        return new RedirectResponse($urlToRedirect);
    }
}
