<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Service\Topic;

use Ares\Forum\Entity\Topic;
use Ares\Forum\Exception\TopicException;
use Ares\Forum\Repository\TopicRepository;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class CreateTopicService
 *
 * @package Ares\Forum\Service\Topic
 */
class CreateTopicService
{
    /**
     * @var TopicRepository
     */
    private TopicRepository $topicRepository;

    /**
     * CreateTopicService constructor.
     *
     * @param   TopicRepository  $topicRepository
     */
    public function __construct(
        TopicRepository $topicRepository
    ) {
        $this->topicRepository = $topicRepository;
    }

    /**
     * @param   array  $data
     *
     * @return CustomResponseInterface
     * @throws TopicException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function execute(array $data)
    {
        $topic = $this->getNewTopic($data);

        $existingTopic = $this->topicRepository->findOneBy([
           'title' => $topic->getTitle()
        ]);

        if ($existingTopic) {
            throw new TopicException(__('There is already a Topic with that title'));
        }

        $topic = $this->topicRepository->save($topic);

        return response()->setData($topic);
    }

    /**
     * @param   array  $data
     *
     * @return Topic
     */
    public function getNewTopic(array $data): Topic
    {
        $topic = new Topic();

        return $topic
            ->setTitle($data['title'])
            ->setDescription($data['description']);
    }
}