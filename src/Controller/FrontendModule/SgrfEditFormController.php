<?php

namespace Contao\SwbGasRegForm\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\Template;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule("sgrf_antrag",
 *   category="miscellaneous", 
 *   template="mod_sgrf_edit_form"
 * )
 */
class SgrfEditFormController extends AbstractFrontendModuleController {

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response {

        $form = $this->createFormBuilder(null)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();

        $template->text = 'Hallo welt 2';
        $template->form = $form->createView();

        return $template->getResponse();
    }

}
