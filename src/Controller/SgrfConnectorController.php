<?php

declare(strict_types=1);

namespace Contao\SwbGasRegForm\Controller;

use Contao\Config;
use Contao\CoreBundle\Controller\AbstractController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\MemberModel;
use Contao\MemberGroupModel;
use Contao\SwbGasRegForm\Model\SwbCompanyModel;
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
class SgrfConnectorController extends AbstractController
{
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
            $isAllowed = $this->userIsAllowed($userid) || $this->isClientAllowed($authenticatedRequest, 'userinfoapps');

            if ($isAllowed) {
                $role = MemberGroupModel::findById($symfonyRequest->get('roleid'));
                $members = MemberModel::findBy(['`groups` LIKE ?', 'username is not null'], ['%"' . $role->id . '"%']);
                $r = [];
                if ($members) {
                    foreach ($members as $member) {
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
                $r = [];
                $roles = MemberGroupModel::findAll();
                foreach ($roles as $role) {
                    $r[] = [$role->id => $role->name];
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
     * @Route("/feuser/{id}", name="sgrf.connector.feuser_by_id", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function feuserDetails(Request $symfonyRequest, $id): Response
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $request = $psrHttpFactory->createRequest($symfonyRequest);
        $response = $psr17Factory->createResponse();

        $server = $this->resService->getServer();

        try {
            $authenticatedRequest = $server->validateAuthenticatedRequest($request);

            $isAllowed = $this->isClientAllowed($authenticatedRequest, 'userinfoapps');
            if ($isAllowed) {
                $userid = intval($id);
                $r['user'] = $userid;
                $member = MemberModel::findById($userid);

                if ($member) {
                    $r['username'] = $member->username;
                    $r['given_name'] = $member->firstname;
                    $r['family_name'] = $member->lastname;
                    $r['email'] = $member->email;
                    $r['company'] = $member->company;
                    $r['street'] = $member->street;
                    $r['postal'] = $member->postal;
                    $r['city'] = $member->city;
                    $r['phone'] = $member->phone;
                    $r['updated'] = intval($member->tstamp);
                    $swbCompanyId = $member->swbCompany;
                    if ($swbCompanyId) {
                        $swbCompany = SwbCompanyModel::findById($swbCompanyId);
                        $r['company'] = $swbCompany->title;
                        $r['street'] = $swbCompany->street . ' ' . $swbCompany->housenumber;
                        $r['postal'] = $swbCompany->postal;
                        $r['city'] = $swbCompany->city;
                        $r['phone'] = $swbCompany->phone;
                        $r['company-email'] = $swbCompany->email;
                        $uuids = \Contao\StringUtil::deserialize($swbCompany->logo, true);
                        if (count($uuids)) {
                            $file = \Contao\FilesModel::findByUuid($uuids[0]);
                            $r['logo'] = $file->path;
                        } else {
                            $r['logo'] = null;
                        }
                    }
                    $r['sgrfausweis'] = $member->sgrfausweis;
                    $groups = $member->getRelated('groups');
                    $r['groups'] = array_map(
                        static function ($g) {
                            return $g->name;
                        },
                        $groups->getModels()
                    );

                    foreach ($r as $k => $v) {
                        if (!is_string($v)) {
                            continue;
                        }
                        $r[$k] = $this->mbconvert($v);
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
     * @Route("/feusermodifications/{since}", name="sgrf.connector.feuser_modifications_since", methods={"GET"}, requirements={"since"="\d+"})
     */
    public function modifiedUsers(Request $symfonyRequest, $since): Response
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $request = $psrHttpFactory->createRequest($symfonyRequest);
        $response = $psr17Factory->createResponse();

        $server = $this->resService->getServer();

        try {
            $authenticatedRequest = $server->validateAuthenticatedRequest($request);

            $isAllowed = $this->isClientAllowed($authenticatedRequest, 'userinfoapps');
            if ($isAllowed) {
                $modifiedUsers = MemberModel::findBy(['tstamp > ?'], [intval($since)], ['limit' => 30, 'order' => 'tstamp ASC']);

                $r = ['users' => []];
                if ($modifiedUsers) {
                    foreach ($modifiedUsers as $modifiedUser) {
                        $r['users'][] = [
                            'id' => intval($modifiedUser->id),
                            'family_name' => $this->mbconvert($modifiedUser->lastname),
                            'given_name' => $this->mbconvert($modifiedUser->firstname),
                            'updated' => intval($modifiedUser->tstamp),
                            'email' => $this->mbconvert($modifiedUser->email),
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

    private function userIsAllowed($userid)
    {
        $member = MemberModel::findById($userid);
        if (!$member) {
            return false;
        }
        $groups = $member->getRelated('groups');

        $configs = $this->getConfig()->get('sgrfconnector');
        $adminroleid = $configs['adminroleid'];

        foreach ($groups as $group) {
            if ($group->id == $adminroleid) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * Check if client is configured for action
     *
     * @param Nyholm\Psr7\ServerRequest $authenticatedRequest The request
     * @return boolean If client is listed
     */
    private function isClientAllowed($authenticatedRequest, $configkey)
    {
        $configs = $this->getConfig()->get('sgrfconnector');
        $client_id = $authenticatedRequest->getAttribute('oauth_client_id');

        return in_array($client_id, $configs[$configkey]);
    }

    /**
     * Multibyte conversion
     * @param string $i Input string
     * @return string The converted string
     */
    private function mbconvert($i)
    {
        # https://www.php.net/manual/en/function.html-entity-decode.php#104617
        return preg_replace_callback("/(&#[0-9]+;)/", function ($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $i);
    }
}
