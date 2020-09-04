<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Photo\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Service\ValidationService;
use Ares\Photo\Entity\Photo;
use Ares\Photo\Exception\PhotoException;
use Ares\Photo\Repository\PhotoRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class PhotoController
 *
 * @package Ares\Photo\Controller
 */
class PhotoController extends BaseController
{
    /**
     * @var PhotoRepository
     */
    private PhotoRepository $photoRepository;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * PhotoController constructor.
     *
     * @param   PhotoRepository         $photoRepository
     * @param   UserRepository          $userRepository
     * @param   DoctrineSearchCriteria  $searchCriteria
     * @param   ValidationService       $validationService
     */
    public function __construct(
        PhotoRepository $photoRepository,
        UserRepository $userRepository,
        DoctrineSearchCriteria $searchCriteria,
        ValidationService $validationService
    ) {
        $this->photoRepository   = $photoRepository;
        $this->searchCriteria    = $searchCriteria;
        $this->validationService = $validationService;
        $this->userRepository = $userRepository;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws PhotoException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function photo(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Photo $photo */
        $photo = $this->photoRepository->get($id);

        if (is_null($photo)) {
            throw new PhotoException(__('No Photo was found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($photo)
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws ValidationException|PhotoException
     */
    public function search(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'username' => 'required'
        ]);

        /** @var string $username */
        $username = $parsedData['username'];

        /** @var User $user */
        $user = $this->userRepository->getByUsername($username);

        if (is_null($user)) {
            throw new PhotoException(__('No Photo was found'), 404);
        }

        /** @var ArrayCollection $photos */
        $photos = $this->photoRepository->findBy([
            'creator' => $user
        ]);

        if (empty($photos)) {
            throw new PhotoException(__('No Photo was found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($photos->toArray())
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws PhotoException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function list(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $this->searchCriteria->setPage((int)$page)
            ->setLimit((int)$resultPerPage)
            ->addOrder('id', 'DESC');

        $photos = $this->photoRepository->paginate($this->searchCriteria);

        if ($photos->isEmpty()) {
            throw new PhotoException(__('No Photos were found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($photos->toArray())
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhotoException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $photo = $this->photoRepository->delete($id);

        if (!$photo) {
            throw new PhotoException(__('Photo couldnt be deleted'));
        }

        return $this->respond(
            $response,
            response()->setData(true)
        );
    }
}
