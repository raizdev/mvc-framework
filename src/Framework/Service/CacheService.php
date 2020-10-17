<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Service;

use Ares\Framework\Exception\CacheException;
use Phpfastcache\Helper\Psr16Adapter as FastCache;

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
     * CacheService constructor.
     *
     * @param FastCache $fastCache
     */
    public function __construct(
        FastCache $fastCache
    ) {
        $this->fastCache = $fastCache;
    }

    /**
     * @param string $key
     *
     * @return bool
     * @throws CacheException
     */
    public function has(string $key): bool
    {
        try {
            if (!$this->isCacheEnabled()) {
                return false;
            }

            return $this->fastCache->has($key);
        } catch (\Exception $exception) {
            throw new CacheException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param string $key
     *
     * @return  mixed
     * @throws CacheException
     */
    public function get(string $key)
    {
        try {
            if (!$this->has($key) || !$this->isCacheEnabled()) {
                return null;
            }

            return $this->fastCache->get($key);
        } catch (\Exception $exception) {
            throw new CacheException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param array $keys
     *
     * @return string
     * @throws CacheException
     */
    public function getMultiple(array $keys): string
    {
        try {
            return $this->fastCache->getMultiple($keys);
        } catch (\Exception $exception) {
            throw new CacheException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     *
     * @return bool
     * @throws CacheException
     */
    public function set(string $key, $value, int $ttl = 0): bool
    {
        try {
            if (!$this->isCacheEnabled()) {
                return false;
            }

            return $this->fastCache->set($key, $value, $this->getTTL($ttl));
        } catch (\Exception $exception) {
            throw new CacheException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param array $values
     * @param int $ttl
     *
     * @return bool
     * @throws CacheException
     */
    public function setMultiple(array $values, int $ttl = 0): bool
    {
        try {
            if (!$this->isCacheEnabled()) {
                return false;
            }

            return $this->fastCache->setMultiple($values, $this->getTTL($ttl));
        } catch (\Exception $exception) {
            throw new CacheException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param string $key
     *
     * @return bool
     * @throws CacheException
     */
    public function delete(string $key): bool
    {
        try {
            if (!$this->isCacheEnabled()) {
                return false;
            }

            return $this->fastCache->delete($key);
        } catch (\Exception $exception) {
            throw new CacheException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param array $keys
     *
     * @return bool
     * @throws CacheException
     */
    public function deleteMultiple(array $keys): bool
    {
        try {
            if (!$this->isCacheEnabled()) {
                return false;
            }

            return $this->fastCache->deleteMultiple($keys);
        } catch (\Exception $exception) {
            throw new CacheException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @return bool
     * @throws CacheException
     */
    public function clear(): bool
    {
        try {
            if (!$this->isCacheEnabled()) {
                return false;
            }

            return $this->fastCache->clear();
        } catch (\Exception $exception) {
            throw new CacheException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param int $ttl
     *
     * @return int|mixed
     * @throws CacheException
     */
    private function getTTL(int $ttl)
    {
        try {
            if (!$ttl) {
                $ttl = $_ENV['CACHE_TTL'];
            }

            return $ttl;
        } catch (\Exception $exception) {
            throw new CacheException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * Check whether Caching is Enabled or not
     *
     * @return bool
     * @throws CacheException
     */
    private function isCacheEnabled(): bool
    {
        try {
            if (!$_ENV['CACHE_ENABLED']) {
                return false;
            }

            return true;
        } catch (\Exception $exception) {
            throw new CacheException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
