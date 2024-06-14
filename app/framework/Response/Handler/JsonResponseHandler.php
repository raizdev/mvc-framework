<?php declare(strict_types=1);
namespace Raizdev\Framework\Response\Handler;

use Raizdev\Framework\Response\PayloadResponse;
use Raizdev\Framework\Response\ResponseType;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Generic JSON response handler.
 */
class JsonResponseHandler extends AbstractResponseHandler
{
    /**
     * Json encode flags.
     *
     * @var int
     */
    protected int $jsonFlags;

    /**
     * JsonResponseHandler constructor.
     *
     * @param ResponseFactoryInterface $responseFactory
     * @param bool $prettify
     */
    public function __construct(ResponseFactoryInterface $responseFactory, bool $prettify = false)
    {
        parent::__construct($responseFactory);

        $jsonFlags = \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES | \JSON_PRESERVE_ZERO_FRACTION;
        if ($prettify) {
            $jsonFlags |= \JSON_PRETTY_PRINT;
        }

        $this->jsonFlags = $jsonFlags;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function handle(ResponseType $responseType): ResponseInterface
    {
        if (!$responseType instanceof PayloadResponse) {
            throw new \InvalidArgumentException(
                \sprintf('Response type should be an instance of %s', PayloadResponse::class)
            );
        }

        $payload = $responseType->getPayload();

        if (!$this->isJsonEncodable($payload)) {
            throw new \InvalidArgumentException('Response type payload is not json encodable');
        }

        $response = $this->getResponse($responseType);
        $response->getBody()->write((string) \json_encode($payload, $this->jsonFlags));

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8');
    }

    /**
     * @param mixed $payload
     *
     * @return bool
     */
    protected function isJsonEncodable(mixed $payload): bool
    {
        if (\is_resource($payload)) {
            return false;
        }

        if (\is_array($payload)) {
            foreach ($payload as $data) {
                if (!$this->isJsonEncodable($data)) {
                    return false;
                }
            }
        }

        return true;
    }
}