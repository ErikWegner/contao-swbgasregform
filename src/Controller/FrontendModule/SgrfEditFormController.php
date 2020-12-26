<?php

namespace Contao\SwbGasRegForm\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * @FrontendModule("sgrf_antrag",
 *   category="miscellaneous", 
 *   template="mod_sgrf_edit_form"
 * )
 */
class SgrfEditFormController extends AbstractFrontendModuleController {

    private $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response {

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();

        $template->text = 'Hallo welt 2';
        $template->form = $form->createView();

        return new Response($this->twig->render(
            'sgrf_edit_form.html.twig',
            [
                'form' => $form,
                'text' => 'Hallo twig welt',
            ]
        )); 
    }

}
