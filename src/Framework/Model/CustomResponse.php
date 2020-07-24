<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Model;

use Ares\Framework\Interfaces\CustomResponseInterface;

/**
 * Class Response
 */
class CustomResponse implements CustomResponseInterface
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $errors;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @return mixed
     */
    public function respond(): string
    {
        $response = [
            'status' => $this->getStatus(),
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'errors' => $this->getErrors(),
            'data' => $this->getData()
        ];

        return json_encode($response);
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        if (!$this->status) {
            return 'ok';
        }

        return $this->status;
    }

    /**
     * @param string $status
     * @return CustomResponseInterface
     */
    public function setStatus(string $status): CustomResponseInterface
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        if (!$this->code) {
            return 200;
        }

        return $this->code;
    }

    /**
     * @param int $code
     * @return CustomResponseInterface
     */
    public function setCode(int $code): CustomResponseInterface
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        if (!$this->message) {
            return '';
        }

        return $this->message;
    }

    /**
     * @param string $message
     * @return CustomResponseInterface
     */
    public function setMessage(string $message): CustomResponseInterface
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        if (!$this->errors) {
            return [];
        }

        return $this->errors;
    }

    /**
     * @param array $errors
     * @return CustomResponseInterface
     */
    public function setErrors(array $errors): CustomResponseInterface
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (!$this->data) {
            return [];
        }

        return $this->data;
    }

    /**
     * @param mixed $data
     * @return CustomResponseInterface
     */
    public function setData($data): CustomResponseInterface
    {
        $this->data = $data;
        return $this;
    }
}