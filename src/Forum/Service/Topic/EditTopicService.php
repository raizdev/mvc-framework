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
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;

/**
 * Class EditTopicService
 *
 * @package Ares\Forum\Service\Topic
 */
class EditTopicService
{
    /**
     * EditTopicService constructor.
     *
     * @param   TopicRepository  $topicRepository
     */
    public function __construct(
        private TopicRepository $topicRepository
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws TopicException
     * @throws DataObjectManagerException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var string $title */
        $title = $data['title'];

        /** @var int $topic */
        $topic_id = $data['topic_id'];

        /** @var string $description */
        $description = $data['description'];

        /** @var Topic $topic */
        $topic = $this->topicRepository->get($topic_id);

        if (!$topic) {
            throw new TopicException(__('Topic not found'));
        }

        $topic
            ->setTitle($title)
            ->setDescription($description)
            ->setUpdatedAt(new \DateTime());

        /** @var Topic $topic */
        $topic = $this->topicRepository->save($topic);

        return response()
            ->setData($topic);
    }
}
