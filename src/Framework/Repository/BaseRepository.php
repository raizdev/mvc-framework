<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Factory\DataObjectManagerFactory;
use Ares\Framework\Model\DataObject;
use Ares\Framework\Model\Query\DataObjectManager;
use Ares\Framework\Service\CacheService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Ares\Framework\Model\Query\Collection;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class BaseRepository
 *
 * @package Ares\Framework\Repository
 */
abstract class BaseRepository
{
    /** @var string */
    private const COLUMN_ID = 'id';

    /**
     * @var string
     */
    protected string $entity;

    /**
     * @var string
     */
    protected string $cachePrefix;

    /**
     * @var string
     */
    protected string $cacheCollectionPrefix;

    /**
     * @var DataObjectManagerFactory
     */
    protected DataObjectManagerFactory $dataObjectManagerFactory;

    /**
     * @var CacheService
     */
    protected CacheService $cacheService;

    /**
     * BaseRepository constructor.
     *
     * @param DataObjectManagerFactory $dataObjectManagerFactory
     * @param CacheService $cacheService
     */
    public function __construct(
        DataObjectManagerFactory $dataObjectManagerFactory,
        CacheService $cacheService
    ) {
        $this->dataObjectManagerFactory = $dataObjectManagerFactory;
        $this->cacheService = $cacheService;
    }

    /**
     * Get DataObject by id or by given field value pair.
     *
     * @param mixed $value
     * @param string $column
     *
     * @param bool $isCached
     * @return DataObject|null
     */
    public function get($value, string $column = self::COLUMN_ID, bool $isCached = true): ?DataObject
    {
        $entity = $this->cacheService->get($this->cachePrefix . $value);

        if ($entity && $isCached) {
            return unserialize($entity);
        }

        $dataObjectManager = $this->dataObjectManagerFactory->create($this->entity);
        $entity = $dataObjectManager->where($column, $value)->first();

        $this->cacheService->set($this->cachePrefix . $value, serialize($entity));

        return $entity;
    }

    /**
     * Get list of data objects by build search.
     *
     * @param DataObjectManager $dataObjectManager
     * @param bool              $isCached
     *
     * @return Collection
     */
    public function getList(DataObjectManager $dataObjectManager, bool $isCached = true): Collection
    {
        $cacheKey = $this->getCacheKey($dataObjectManager);

        $collection = $this->cacheService->get($this->cacheCollectionPrefix . $cacheKey);

        if ($collection && $isCached) {
            return unserialize($collection);
        }

        $collection = $dataObjectManager->get();

        $this->cacheService->set($this->cacheCollectionPrefix . $cacheKey, serialize($collection));

        $this->addSingleCache($collection);
        return $collection;
    }

    /**
     * Get paginated list of data objects by build search.
     *
     * @param DataObjectManager $dataObjectManager
     * @param int $pageNumber
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getPaginatedList(
        DataObjectManager $dataObjectManager,
        int $pageNumber,
        int $limit
    ): LengthAwarePaginator {
        $cacheKey = $this->getCacheKey($dataObjectManager, (string) $pageNumber, (string) $limit);

        $collection = $this->cacheService->get($this->cacheCollectionPrefix . $cacheKey);

        if ($collection) {
            return unserialize($collection);
        }

        $collection = $dataObjectManager->paginate($limit, ['*'], 'page', $pageNumber);

        $this->cacheService->set($this->cacheCollectionPrefix . $cacheKey, serialize($collection));

        $this->addSingleCache($collection);
        return $collection;
    }

    /**
     * Saves or updates given entity.
     *
     * @param DataObject $entity
     * @return DataObject
     * @throws DataObjectManagerException
     */
    public function save(DataObject $entity): DataObject
    {
        $dataObjectManager = $this->dataObjectManagerFactory->create($this->entity);

        $id = $entity->getData(self::COLUMN_ID);

        try {
            if ($id) {
                $dataObjectManager
                    ->where(self::COLUMN_ID, $id)
                    ->update($entity->getData());

                return $this->get($entity->getId(), $entity::PRIMARY_KEY) ?? $entity;
            }

            $newId = $dataObjectManager->insertGetId($entity->getData(), $entity::PRIMARY_KEY);
            return $this->get($newId, $entity::PRIMARY_KEY) ?? $entity;
        } catch (\Exception $exception) {
            throw new DataObjectManagerException(
                $exception->getMessage(),
                500,
                $exception
            );
        }
    }

    /**
     * Delete by id.
     *
     * @param int $id
     * @return bool
     * @throws DataObjectManagerException
     */
    public function delete(int $id): bool
    {
        $dataObjectManager = $this->dataObjectManagerFactory->create($this->entity);

        try {
            return (bool) $dataObjectManager->delete($id);
        } catch (\Exception $exception) {
            throw new DataObjectManagerException(
                $exception->getMessage(),
                500,
                $exception
            );
        }
    }

    /**
     * Returns one to one relation.
     *
     * @param BaseRepository $repository
     * @param int $id
     * @param string $column
     * @return DataObject|null
     */
    public function getOneToOne(BaseRepository $repository, int $id, string $column): ?DataObject
    {
        return $repository->get($id, $column);
    }

    /**
     * Returns one to many relation.
     *
     * @param BaseRepository $repository
     * @param int $id
     * @param string $column
     * @return Collection
     */
    public function getOneToMany(BaseRepository $repository, int $id, string $column): Collection
    {
        $dataObject = $repository->getDataObjectManager()->where($column, $id);

        return $repository->getList($dataObject);
    }

    /**
     * Returns many to many relation.
     *
     * @param BaseRepository $repository
     * @param int $id
     * @param string $pivotTable
     * @param string $primaryPivotColumn
     * @param string $foreignPivotColumn
     * @return Collection
     */
    public function getManyToMany(
        BaseRepository $repository,
        int $id,
        string $pivotTable,
        string $primaryPivotColumn,
        string $foreignPivotColumn
    ): Collection {
        $primaryTable = $this->entity::TABLE;
        $primaryTableColumn = $this->entity::PRIMARY_KEY;
        $foreignTable = $repository->getEntity()::TABLE;
        $foreignTableColumn = $repository->getEntity()::PRIMARY_KEY;

        $dataObject = $repository->getDataObjectManager()
            ->select([$foreignTable . '.*'])
            ->join(
                $pivotTable,
                $foreignTable . '.' . $foreignTableColumn,
                '=',
                $pivotTable . '.' . $foreignPivotColumn
            )
            ->join(
                $primaryTable,
                $primaryTable . '.' . $primaryTableColumn,
                '=',
                $pivotTable . '.' . $primaryPivotColumn
            )
            ->where($primaryTable . '.' . $primaryTableColumn, $id);

        return $repository->getList($dataObject);
    }

    /**
     * Generates cache key.
     *
     * @param DataObjectManager $dataObjectManager
     * @param string ...$postfix
     * @return string
     */
    protected function getCacheKey(DataObjectManager $dataObjectManager, string ...$postfix): string
    {
        $sql = $dataObjectManager->toSql();
        $bindings = $dataObjectManager->getBindings();

        $cacheKey = vsprintf(str_replace("?", "%s", $sql), $bindings) . implode($postfix);

        return hash('tiger192,3', $cacheKey);
    }

    /**
     * Iterates collection and adds single cache for each entity.
     *
     * @param Collection|LengthAwarePaginator $collection
     * @return void
     */
    private function addSingleCache($collection): void
    {
        /** @var DataObject $item */
        foreach ($collection as &$item) {
            $this->cacheService->set($this->cachePrefix . $item->getData($item::PRIMARY_KEY), $item);
        }
    }

    /**
     * Returns data object manager.
     *
     * @return DataObjectManager
     */
    public function getDataObjectManager(): DataObjectManager
    {
        return $this->dataObjectManagerFactory->create($this->entity);
    }

    /**
     * Returns entity of repository.
     *
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }
}
