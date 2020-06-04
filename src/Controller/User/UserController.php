<?php


namespace App\Controller\User;


use App\Controller\BaseController;
use App\Service\User\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class UserController
 */
class UserController extends BaseController
{
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * UserController constructor.
     *
     * @param   UserService  $userService
     */
    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     */
    public function getAll(Request $request, Response $response): Response
    {
        $user = $this->userService->fetchAll();

        return $this->jsonResponse(
            $response,
            $user,
            200
        );
    }
}