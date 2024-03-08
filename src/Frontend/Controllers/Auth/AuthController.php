<?php
namespace Orion\Controllers\Auth;

use Odan\Session\SessionInterface;
use Sunrise\Http\Router\Annotation as Mapping;
use Psr\Http\Message\ServerRequestInterface as Request;
use Sunrise\Http\Message\ResponseFactory;
use Sunrise\Http\Router\Annotation\Prefix;
use Sunrise\Http\Router\Annotation\Route;

use StarreDEV\Framework\Service\TwigService;
use StarreDEV\Framework\Controller\BaseController;
use StarreDEV\User\Interfaces\UserInterface;
use StarreDEV\User\Service\Auth\DetermineIpService;
use StarreDEV\User\Service\Auth\LoginService;
use StarreDEV\User\Service\Auth\RegisterService;

#[Prefix('/auth')]
class AuthController extends BaseController {

    public function __construct(
        private DetermineIpService $determineIpService,
        private LoginService $loginService,
        private RegisterService $registerService,
        private SessionInterface $session,
        private TwigService $twig,
        private ResponseFactory $response
    ) {}

    #[Route(
        name: 'sign-in',
        path: '/sign-in',
        methods: ['POST'],
    )]

    /**
     * AuthController Login Method
     * 
     * @param Request  $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     */
    public function login(Request $request) 
    {     
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();
      
        $this->session->getFlash()->add('test', 'test bericht');
      
        /** @var string $determinedIp */
        $determinedIp = $this->determineIpService->execute();

        $parsedData[UserInterface::COLUMN_IP_CURRENT] = $determinedIp;

        $customResponse = $this->loginService->login($parsedData);

        return $this->twig->render(
            $this->response->createResponse(200),
            'home/home.twig'
        );
    }

    #[Route(
        name: 'account-registration',
        path: '/account-registration',
        methods: ['POST'],
    )]

    /**
     * AuthController Signup Method
     * 
     * @param Request  $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     */
    public function register(Request $request)
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        /** @var string $determinedIp */
        $determinedIp = $this->determineIpService->execute();

        $parsedData[UserInterface::COLUMN_IP_CURRENT] = $determinedIp;
        $parsedData[UserInterface::COLUMN_IP_REGISTER] = $determinedIp;
        $parsedData[UserInterface::COLUMN_ACCOUNT_CREATED] = time();

        $customResponse = $this->registerService->register($parsedData);

        return $this->twig->render(
            $this->response->createResponse(200),
            'home/home.twig'
        );
    }

    #[Route(
        name: 'logout',
        path: '/logout',
        methods: ['GET'],
    )]

    /**
     * Returns a response without the Authorization header
     * We could blacklist the token with redis-cache
     *
     * @param Request $request
     *
     * @return Response Returns a Response with the given Data
     */
    public function logout(Request $request)
    {
        $this->session->destroy();

        return (new ResponseFactory)->createResponse(200)->withHeader('Location', '/');
    }
}