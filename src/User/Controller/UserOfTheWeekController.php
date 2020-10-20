<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\User\Repository\UserOfTheWeekRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @TODO Create User Of The Week based on likes
 *
 * Class UserOfTheWeekController
 *
 * @package Ares\User\Controller
 */
class UserOfTheWeekController extends BaseController
{
    /**
     * @var UserOfTheWeekRepository
     */
    private UserOfTheWeekRepository $userOfTheWeekRepository;

    /**
     * UserOfTheWeekController constructor.
     *
     * @param   UserOfTheWeekRepository  $userOfTheWeekRepository
     */
    public function __construct(
        UserOfTheWeekRepository $userOfTheWeekRepository
    ) {
        $this->userOfTheWeekRepository = $userOfTheWeekRepository;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     */
    public function get(Request $request, Response $response): Response
    {
    }
}
