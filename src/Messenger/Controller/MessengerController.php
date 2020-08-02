<?php


namespace Ares\Messenger\Controller;


use Ares\Framework\Controller\BaseController;
use Ares\Messenger\Exception\MessengerException;
use Ares\Messenger\Repository\MessengerRepository;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MessengerController extends BaseController
{
    /**
     * @var MessengerRepository
     */
    private MessengerRepository $messengerRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * MessengerController constructor.
     *
     * @param MessengerRepository $messengerRepository
     * @param UserRepository      $userRepository
     */
    public function __construct(
        MessengerRepository $messengerRepository,
        UserRepository $userRepository
    ) {
        $this->messengerRepository = $messengerRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws UserException
     * @throws MessengerException
     */
    public function friends(Request $request, Response $response): Response
    {
        /** @var array $friends */
        $friends = $this->messengerRepository->getList([
            'user' => $this->getUser($this->userRepository, $request)
        ]);

        if(empty($friends)) {
            throw new MessengerException(__('You have no friends'), 404);
        }

        $list = [];
        foreach ($friends as $friend) {
            $list[] = $friend
                ->getFriend()
                ->getArrayCopy();
        }

        return $this->respond(
            $response,
            response()->setData($list)
        );
    }
}
