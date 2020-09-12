<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guestbook\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Guestbook\Entity\Guestbook;
use Ares\Guestbook\Repository\GuestbookRepository;
use Ares\User\Entity\User;

/**
 * Class CreateGuestbookEntryService
 *
 * @package Ares\Guestbook\Service
 */
class CreateGuestbookEntryService
{
    /**
     * @var GuestbookRepository
     */
    private GuestbookRepository $guestbookRepository;

    /**
     * CreateGuestbookEntryService constructor.
     *
     * @param   GuestbookRepository  $guestbookRepository
     */
    public function __construct(
        GuestbookRepository $guestbookRepository
    ) {
        $this->guestbookRepository = $guestbookRepository;
    }

    /**
     * @param   User   $user
     * @param   array  $data
     *
     * @return CustomResponseInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        $entry = $this->getNewEntry($user, $data);

        $entry = $this->guestbookRepository->save($entry);

        return response()->setData($entry);
    }

    /**
     * @param   User   $user
     * @param   array  $data
     *
     * @return Guestbook
     */
    private function getNewEntry(User $user, array $data): Guestbook
    {
        $entry = new Guestbook();

        return $entry
            ->setContent($data['content'])
            ->setUser($user)
            ->setProfile($data['profile'])
            ->setGuild($data['guild'])
            ->setLikes(0)
            ->setDislikes(0);
    }
}
