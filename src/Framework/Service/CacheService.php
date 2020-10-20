<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Service;

use Phpfastcache\Helper\Psr16Adapter as FastCache;
use Psr\Log\LoggerInterface;

/**
 * Class CacheService
 *
 * @package Ares\Framework\Service
 */
class CacheService
{
    /**
     * @var FastCache
     */
    private FastCache $fastCache;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * CacheService constructor.
     *
     * @param FastCache $fastCache
     * @param LoggerInterface $logger
     */
    public function __construct(
        FastCache $fastCache,
        LoggerInterface $logger
    ) {
        $this->fastCache = $fastCache;
        $this->logger = $logger;
    }

    /**
     * Checks whether cache is set or not.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        try {
            if (!$this->isCacheEnabled()) {
                return false;
            }

            return $this->fastCache->has($key);
        } catch (\Exception $exception) {
            $this->logger->error(
                $exception->getMessage()
            );
        }
    }

    /**
     * Get single cache entry.
     *
     * @param string $key
     * @return  mixed
     */
    public function get(string $key)
    {
        try {
            if (!$this->has($key) || !$this->isCacheEnabled()) {
                return null;
            }

            return $this->fastCache->get($key);
        } catch (\Exception $exception) {
            $this->logger->error(
                $exception->getMessage()
            );

            return null;
        }
    }

    /**
     * Get multiple cache entries by keys.
     *
     * @param array $keys
     * @return array
     */
    public function getMultiple(array $keys): array
    {
        try {
            return $this->fastCache->getMultiple($keys);
        } catch (\Exception $exception) {
            $this->logger->error(
                $exception->getMessage()
            );

            return [];
        }
    }

    /**
     * Set single cache entry.
     *
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     * @return bool
     */
    public function set(string $key, $value, int $ttl = 0): bool
    {
        try {
            if (!$this->isCacheEnabled()) {
                return false;
            }

            return $this->fastCache->set($key, $value, $this->getTTL($ttl));
        } catch (\Exception $exception) {
            $this->logger->error(
                $exception->getMessage()
            );

            return false;
        }
    }

    /**
     * Sets multiple cache entries.
     *
     * @param array $values
     * @param int $ttl
     * @return bool
     */
    public function setMultiple(array $values, int $ttl = 0): bool
    {
        try {
            if (!$this->isCacheEnabled()) {
                return false;
            }

            return $this->fastCache->setMultiple($values, $this->getTTL($ttl));
        } catch (\Exception $exception) {
            $this->logger->error(
                $exception->getMessage()
            );

            return false;
        }
    }

    /**
     * Cleares specific cache entry by key.
     *
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        try {
            if (!$this->isCacheEnabled()) {
                return false;
            }

            return $this->fastCache->delete($key);
        } catch (\Exception $exception) {
            $this->logger->error(
                $exception->getMessage()
            );
        }
    }

    /**
     * Cleares multiple cache entries by keys.
     *
     * @param array $keys
     * @return bool
     */
    public function deleteMultiple(array $keys): bool
    {
        try {
            if (!$this->isCacheEnabled()) {
                return false;
            }

            return $this->fastCache->deleteMultiple($keys);
        } catch (\Exception $exception) {
            $this->logger->error(
                $exception->getMessage()
            );

            return false;
        }
    }

    /**
     * Cleares cache.
     *
     * @return bool
     */
    public function clear(): bool
    {
        try {
            if (!$this->isCacheEnabled()) {
                return false;
            }

            return $this->fastCache->clear();
        } catch (\Exception $exception) {
            $this->logger->error(
                $exception->getMessage()
            );

            return false;
        }
    }

    /**
     * Returns ttl for cache.
     *
     * @param int $ttl
     * @return int|mixed
     */
    private function getTTL(int $ttl)
    {
        if (!$ttl) {
            $ttl = $_ENV['CACHE_TTL'];
        }

        return $ttl;
    }

    /**
     * Check whether Caching is Enabled or not.
     *
     * @return bool
     */
    private function isCacheEnabled(): bool
    {
        if (!$_ENV['CACHE_ENABLED']) {
            return false;
        }

        return true;
    }
}
