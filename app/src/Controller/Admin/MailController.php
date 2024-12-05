<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Shopsys\FrameworkBundle\Controller\Admin\MailController as baseMailController;

/**
 * @property \App\Model\Mail\MailTemplateFacade $mailTemplateFacade
 * @property \App\Model\Mail\Grid\MailTemplateGridFactory $mailTemplateGridFactory
 * @property \App\Model\Mail\MailTemplateDataFactory $mailTemplateDataFactory
 * @method __construct(\Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade $adminDomainTabsFacade, \App\Model\Mail\MailTemplateFacade $mailTemplateFacade, \App\Model\Mail\Setting\MailSettingFacade $mailSettingFacade, \Shopsys\FrameworkBundle\Model\AdminNavigation\BreadcrumbOverrider $breadcrumbOverrider, \App\Model\Mail\Grid\MailTemplateGridFactory $mailTemplateGridFactory, \App\Model\Mail\MailTemplateConfiguration $mailTemplateConfiguration, \App\Model\Mail\MailTemplateDataFactory $mailTemplateDataFactory, \Shopsys\FrameworkBundle\Model\Mail\MailTemplateSender\MailTemplateSenderFacade $mailTemplateSenderFacade, \Shopsys\FrameworkBundle\Component\FlashMessage\ErrorExtractor $errorExtractor)
 * @property \App\Model\Mail\Setting\MailSettingFacade $mailSettingFacade
 * @method \App\Model\Administrator\Administrator getCurrentAdministrator()
 * @property \App\Model\Mail\MailTemplateConfiguration $mailTemplateConfiguration
 */
class MailController extends baseMailController
{
}
