<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Middleware;

use Ares\Framework\Exception\ThrottleException;
use Predis\Client as Predis;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ThrottleMiddleware
 *
 * @package Ares\Framework\Middleware
 */
class ThrottleMiddleware implements MiddlewareInterface
{
    /**
     * @var int
     */
    private int $requests = 30;

    /**
     * @var int
     */
    private int $perSecond = 60;

    /**
     * @var mixed|string
     */
    private string $identifier;

    /**
     * @var string
     */
    private string $storageKey = 'rate:%s:requests';

    /**
     * @var Predis
     */
    private Predis $predis;

    /**
     * ThrottleMiddleware constructor.
     *
     * @param Predis $predis
     */
    public function __construct(
        Predis $predis
    ) {
        $this->predis = $predis;
        $this->identifier = $this->getIdentifier();
    }

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws ThrottleException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if ($this->hasExceededRateLimit()) {
            throw new ThrottleException('There was an issue with your request, try again', 429);
        }

        $this->incrementRequestCount();

        return $handler->handle($request);
    }

    /**
     * Check if the rate limit has been exceeded.
     *
     * @return boolean
     */
    private function hasExceededRateLimit(): bool
    {
        if ($this->predis->get($this->getStorageKey()) >= $this->requests) {
            return true;
        }

        return false;
    }

    /**
     * Increment the request count.
     *
     * @return void
     */
    private function incrementRequestCount(): void
    {
        $this->predis->incr($this->getStorageKey());
        $this->predis->expire($this->getStorageKey(), $this->perSecond);
    }

    /**
     * Set the limitations.
     *
     * @param integer $requests
     * @param integer $perSecond
     *
     * @return $this
     */
    public function setRateLimit($requests, $perSecond): self
    {
        $this->requests = $requests;
        $this->perSecond = $perSecond;

        return $this;
    }

    /**
     * Set the storage key to be used for Redis.
     *
     * @param string $storageKey
     *
     * @return $this
     */
    public function setStorageKey(string $storageKey): self
    {
        $this->storageKey = $storageKey;

        return $this;
    }

    /**
     * Get the identifier for the Redis storage key.
     *
     * @return string
     */
    private function getStorageKey(): string
    {
        return sprintf($this->storageKey, $this->identifier);
    }

    /**
     * @param $identifier
     *
     * @return $this
     */
    public function setIdentifier($identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @param null $identifier
     *
     * @return mixed
     */
    private function getIdentifier($identifier = null)
    {
        if ($identifier === null) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return $identifier;
    }
}
