<?php

namespace Contao\SwbGasRegForm\Controller\FrontendModule;

use Contao\SwbGasRegForm\Forms\RegistrationFormType;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\Template;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Contao\SwbGasRegForm\Model\SgrfFormsModel;

/**
 * @FrontendModule("sgrf_antrag",
 *   category="miscellaneous", 
 *   template="mod_sgrf_edit_form"
 * )
 */
class SgrfEditFormController extends AbstractFrontendModuleController {

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response {

        $formEntityId = $request->query->get('sgrfid');
        $regdata = SgrfFormsModel::findById($formEntityId);
        $form = $this->createForm(RegistrationFormType::class, $regdata);

        //$reg = new SgrfFormsModel();
        //$reg->setCity($model->getCity());
        //$form = $this->createForm(RegistrationFormType::class, $reg);

        $form->handleRequest($request);

        $user = $this->get('security.helper')->getUser();

        $template->text = 'Hallo welt 2: ' . $user->username . " # " . $model->id . ' + ' . $formEntityId;
        $template->form = $form->createView();

        return $template->getResponse();
    }

}
