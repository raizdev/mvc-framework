<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guestbook\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Service\ValidationService;
use Ares\Guestbook\Exception\GuestbookException;
use Ares\Guestbook\Repository\GuestbookRepository;
use Ares\Guestbook\Service\CreateGuestbookEntryService;
use Ares\Guild\Entity\Guild;
use Ares\Guild\Repository\GuildRepository;
use Ares\User\Entity\User;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class GuestbookController
 *
 * @package Ares\Guestbook\Controller
 */
class GuestbookController extends BaseController
{
    /**
     * @var GuestbookRepository
     */
    private GuestbookRepository $guestbookRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var CreateGuestbookEntryService
     */
    private CreateGuestbookEntryService $createGuestbookEntryService;

    /**
     * @var GuildRepository
     */
    private GuildRepository $guildRepository;

    /**
     * GuestbookController constructor.
     *
     * @param   GuestbookRepository          $guestbookRepository
     * @param   UserRepository               $userRepository
     * @param   GuildRepository              $guildRepository
     * @param   DoctrineSearchCriteria       $searchCriteria
     * @param   ValidationService            $validationService
     * @param   CreateGuestbookEntryService  $createGuestbookEntryService
     */
    public function __construct(
        GuestbookRepository $guestbookRepository,
        UserRepository $userRepository,
        GuildRepository $guildRepository,
        DoctrineSearchCriteria $searchCriteria,
        ValidationService $validationService,
        CreateGuestbookEntryService $createGuestbookEntryService
    ) {
        $this->guestbookRepository = $guestbookRepository;
        $this->userRepository = $userRepository;
        $this->guildRepository = $guildRepository;
        $this->searchCriteria = $searchCriteria;
        $this->validationService = $validationService;
        $this->createGuestbookEntryService = $createGuestbookEntryService;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws ValidationException
     * @throws UserException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException|GuestbookException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'content' => 'required',
            'profile' => 'numeric',
            'guild' => 'numeric'
        ]);

        /** @var int $profile_id */
        $profile_id = $parsedData['profile'] ?? 0;

        /** @var int $guild_id */
        $guild_id = $parsedData['guild'] ?? 0;

        /** @var User $user */
        $user = $this->getUser($this->userRepository, $request, false);

        /** @var User $profile */
        $profile = $this->userRepository->get($profile_id, false);

        /** @var Guild $guild */
        $guild = $this->guildRepository->get($guild_id, false);

        if (!$profile && !$guild) {
            throw new GuestbookException(__('The associated Entities couldnt be found'));
        }

        $parsedData['profile'] = $profile;
        $parsedData['guild'] = $guild;

        $customResponse = $this->createGuestbookEntryService->execute($user, $parsedData);

        return $this->respond($response, $customResponse);
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws PhpfastcacheSimpleCacheException|InvalidArgumentException
     */
    public function profileList(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var int $type */
        $profileId = $args['profile_id'];

        $this->searchCriteria->setPage((int)$page)
            ->setLimit((int)$resultPerPage)
            ->addFilter('profile', $profileId)
            ->addOrder('id', 'DESC');

        /** @var ArrayCollection $pinnedArticles */
        $entries = $this->guestbookRepository->paginate($this->searchCriteria, false);

        return $this->respond(
            $response,
            response()->setData([
                'pagination' => [
                    'totalPages' => $entries->getPages(),
                    'prevPage' => $entries->getPrevPage(),
                    'nextPage' => $entries->getNextPage()
                ],
                'entries' => $entries->toArray(),
                'totalEntries' => $entries->getTotal()
            ])
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws PhpfastcacheSimpleCacheException|InvalidArgumentException
     */
    public function guildList(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var int $guildId */
        $guildId = $args['guild_id'];

        $this->searchCriteria->setPage((int)$page)
            ->setLimit((int)$resultPerPage)
            ->addFilter('guild', $guildId)
            ->addOrder('id', 'DESC');

        /** @var ArrayCollection $pinnedArticles */
        $entries = $this->guestbookRepository->paginate($this->searchCriteria, false);

        return $this->respond(
            $response,
            response()->setData([
                'pagination' => [
                    'totalPages' => $entries->getPages(),
                    'prevPage' => $entries->getPrevPage(),
                    'nextPage' => $entries->getNextPage()
                ],
                'entries' => $entries->toArray(),
                'totalEntries' => $entries->getTotal()
            ])
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws GuestbookException
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function delete(Request $request, Response $response, $args): Response
    {
        $id = (int) $args['id'];

        $deleted = $this->guestbookRepository->delete($id);

        if (!$deleted) {
            throw new GuestbookException(__('Guestbook Entry could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()->setData(true)
        );
    }
}
