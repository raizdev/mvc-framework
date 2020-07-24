<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Interfaces;

/**
 * Interface ResponseInterface
 */
interface CustomResponseInterface
{
    /**
     * @return string
     */
    public function getJson(): string;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param string $status
     * @return CustomResponseInterface
     */
    public function setStatus(string $status): CustomResponseInterface;

    /**
     * @return int
     */
    public function getCode(): int;

    /**
     * @param int $code
     * @return CustomResponseInterface
     */
    public function setCode(int $code): CustomResponseInterface;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @param string $message
     * @return CustomResponseInterface
     */
    public function setMessage(string $message): CustomResponseInterface;

    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * @param array $errors
     * @return CustomResponseInterface
     */
    public function setErrors(array $errors): CustomResponseInterface;

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param mixed $data
     * @return CustomResponseInterface
     */
    public function setData($data): CustomResponseInterface;
}