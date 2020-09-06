<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Service\Thread;

use Ares\Forum\Entity\Thread;
use Ares\Forum\Exception\ThreadException;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Forum\Repository\TopicRepository;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CreateThreadService
 *
 * @package Ares\Forum\Service\Thread
 */
class CreateThreadService
{
    /**
     * @var ThreadRepository
     */
    private ThreadRepository $threadRepository;

    /**
     * @var TopicRepository
     */
    private TopicRepository $topicRepository;

    /**
     * @var Slugify
     */
    private Slugify $slug;

    /**
     * CreateThreadService constructor.
     *
     * @param ThreadRepository $threadRepository
     * @param TopicRepository  $topicRepository
     * @param Slugify          $slug
     */
    public function __construct(
        ThreadRepository $threadRepository,
        TopicRepository $topicRepository,
        Slugify $slug
    ) {
        $this->threadRepository = $threadRepository;
        $this->topicRepository = $topicRepository;
        $this->slug = $slug;
    }

    /**
     * @param   User   $user
     * @param   array  $data
     *
     * @return CustomResponseInterface
     * @throws ThreadException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        $topic = $this->getNewThread($user, $data);

        $topic = $this->threadRepository->save($topic);

        return response()->setData($topic);
    }

    /**
     * @param   User   $user
     * @param   array  $data
     *
     * @return Thread
     * @throws ThreadException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function getNewThread(User $user, array $data): Thread
    {
        $thread = new Thread();

        $topic = $this->topicRepository->get($data['topic_id'], false);
        $existingThread = $this->threadRepository->findOneBy([
            'title' => $data['title']
        ]);

        if (!$topic) {
            throw new ThreadException(__('Related Topic was not found'));
        }

        if ($existingThread) {
            throw new ThreadException(__('There is already an existing Thread with this Title'));
        }

        return $thread
            ->setUser($user)
            ->setTopic($topic)
            ->setSlug($this->slug->slugify($data['title']))
            ->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setContent($data['content'])
            ->setLikes(0)
            ->setDislikes(0);
    }
}
