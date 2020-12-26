<?php

namespace Contao\SwbGasRegForm\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule("sgrf_antrag",
 *   category="miscellaneous", 
 *   template="mod_sgrf_edit_form"
 * )
 */
class SgrfEditFormController extends AbstractFrontendModuleController
{
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        return $template->getResponse();
    }
}
