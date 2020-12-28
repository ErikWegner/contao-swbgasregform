<?php

namespace Contao\SwbGasRegForm\Controller;

use Contao\CoreBundle\Security\Authentication\Token\TokenChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Terminal42\ServiceAnnotationBundle\Annotation\ServiceTag;
use Contao\CoreBundle\Controller\AbstractController;

/**
 * @Route("/sgrf/token", name=TokenController::class, defaults={"_scope": "frontend"})
 * @ServiceTag("controller.service_arguments")
 */
class TokenController extends AbstractController {

    /**
     * @var TokenChecker
     */
    private $tokenChecker;

    public function __construct(TokenChecker $tokenChecker) {
        $this->tokenChecker = $tokenChecker;
    }

    public function __invoke(Request $request): Response {
        $feuser = $this->get('security.authorization_checker')->isGranted('ROLE_MEMBER');
        
        if ($feuser) {
            $user = $this->getUser();
            // es gibt einen authentifizierten Frontend-Benutzer
            $array = array('authenticated' => true);
            $array['user'] = $user->id;
            $response = new Response(json_encode($array), 200);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        else {
            // es gibt keinen authentifizierten Frontend-Benutzer
            $array = array('authenticated' => $feuser);
            $response = new Response(json_encode($array), 401);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

}
