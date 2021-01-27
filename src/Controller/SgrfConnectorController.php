<?php

declare(strict_types=1);

namespace Contao\SwbGasRegForm\Controller;

use Contao\Config;
use Contao\CoreBundle\Controller\AbstractController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\MemberModel;
use Contao\MemberGroupModel;
use ErikWegner\FeOpenidProvider\Service\ResourceServerService;
use League\OAuth2\Server\Exception\OAuthServerException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Terminal42\ServiceAnnotationBundle\Annotation\ServiceTag;

/**
 * @Route("/sgrf/connector", name=SgrfConnectorController::class, defaults={"_scope": "frontend"})
 * @ServiceTag("controller.service_arguments")
 */
class SgrfConnectorController extends AbstractController {
    /**
     * @var ResourceServerService ResourceServerService
     */
    private $resService;

    /**
     * @var Config The configuration
     */
    private $config;

    /**
     * @var ContaoFramework
     */
    private $framework;

    public function __construct(
        ContaoFramework $framework,
        ResourceServerService $resService
    ) {
        $this->framework = $framework;
        $this->resService = $resService;
    }

    /**
     * @Route("/usersWithRole", name="sgrf.connector.userswithrole", methods={"GET"})
     */
    public function usersWithRole(Request $symfonyRequest): Response
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $request = $psrHttpFactory->createRequest($symfonyRequest);
        $response = $psr17Factory->createResponse();

        $server = $this->resService->getServer();

        try {
            $authenticatedRequest = $server->validateAuthenticatedRequest($request);
            $userid = $authenticatedRequest->getAttribute('oauth_user_id');
            $isAllowed = $this->userIsAllowed($userid);

            if ($isAllowed) {
                $role = MemberGroupModel::findById($symfonyRequest->get('roleid'));
                $members = MemberModel::findBy(['groups LIKE ?', 'username is not null'], ['%"' . $role->id . '"%']);
                $r = [];
                if ($members) {
                    foreach($members as $member) {
                        $r[] = [
                            'id' => $member->id,
                            'u' => $member->username,
                            ];
                    }
                }

                $response = new Response(json_encode($r), 200);
            } else {
                $response = new Response(json_encode(['error' => 'Not allowed.']), 403);
            }

            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } catch (OAuthServerException $exception) {
            $httpFoundationFactory = new HttpFoundationFactory();

            return $httpFoundationFactory->createResponse($exception->generateHttpResponse($response));
        }
    }
    
    /**
     * @Route("/feroles", name="sgrf.connector.feroles", methods={"GET"})
     */
    public function frontendRoles(Request $symfonyRequest): Response
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $request = $psrHttpFactory->createRequest($symfonyRequest);
        $response = $psr17Factory->createResponse();

        $server = $this->resService->getServer();

        try {
            $authenticatedRequest = $server->validateAuthenticatedRequest($request);
            $userid = $authenticatedRequest->getAttribute('oauth_user_id');
            $isAllowed = $this->userIsAllowed($userid);

            if ($isAllowed) {
                // TODO: list all users by role
                $r = [];
                $roles = MemberGroupModel::findAll();
                foreach($roles as $role) {
                    $r[] = [$role->id => $role-> name];
                }

                $response = new Response(json_encode($r), 200);
            } else {
                $response = new Response(json_encode(['error' => 'Not allowed.']), 403);
            }

            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } catch (OAuthServerException $exception) {
            $httpFoundationFactory = new HttpFoundationFactory();

            return $httpFoundationFactory->createResponse($exception->generateHttpResponse($response));
        }
    }

    /**
     * Gets the configuration.
     *
     * @return Config The Configuration
     */
    protected function getConfig()
    {
        if (!isset($this->config)) {
            $this->framework->initialize();
            $this->config = $this->framework->getAdapter(Config::class);
        }

        return $this->config;
    }
    
    private function userIsAllowed($userid) {
        $member = MemberModel::findById($userid);
        $groups = $member->getRelated('groups');

        $configs = $this->getConfig()->get('sgrfconnector');
        $adminroleid = $configs['adminroleid'];

        foreach ($groups as $group) {
            if ($group->id==$adminroleid) {
                return true;
                break;
            }
        }
        
        return false;
    }
}
