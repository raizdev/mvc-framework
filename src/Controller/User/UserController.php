<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Repository\User\UserRepository;
use League\Container\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class UserController
 */
class UserController extends BaseController
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * UserController constructor.
     *
     * @param   Container  $container
     */
    public function __construct(
        Container $container
    ) {
        parent::__construct($container);
        $this->userRepository = $container->get('userRepository');
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     */
    public function all(Request $request, Response $response): Response
    {
        $user = $this->userRepository->all();

        return $this->jsonResponse(
            $response,
            $user,
            200
        );
    }
}