<?php
namespace StarreDEV\Framework\Model;

use StarreDEV\Framework\Interfaces\CustomResponseInterface;
use StarreDEV\Framework\Interfaces\HttpResponseCodeInterface;

class CustomResponse implements CustomResponseInterface
{

    private string $status = '';
    private int|string $code = 0;
    private string $exception = '';
    private array $errors = [];
    private mixed $data;

    public function getJson(): string
    {
        if (!$this->exception || !$this->errors) {
            $response = $this->getSuccessResponse();
        } else {
            $response = $this->getErrorResponse();
        }

        return json_encode($response);
    }

    private function getSuccessResponse(): array
    {
        return [
            'status' => $this->getStatus(),
            'code' => $this->getCode(),
            'data' => $this->getData()
        ];
    }

    private function getErrorResponse(): array
    {
        return [
            'status' => $this->getStatus(),
            'code' => $this->getCode(),
            'exception' => $this->getException(),
            'errors' => $this->getErrors()
        ];
    }

    public function getStatus(): string
    {
        if (!$this->status) {
            return 'ok';
        }

        return $this->status;
    }

    public function setStatus(string $status): CustomResponseInterface
    {
        $this->status = $status;
        return $this;
    }

    public function getCode(): int|string
    {
        if (!$this->code) {
            return HttpResponseCodeInterface::HTTP_RESPONSE_OK;
        }

        return $this->code;
    }

    public function setCode(int|string $code): CustomResponseInterface
    {
        $this->code = $code;
        return $this;
    }

    public function getException(): string
    {
        if (!$this->exception) {
            return '';
        }

        return $this->exception;
    }

    public function setException(string $exception): CustomResponseInterface
    {
        $this->exception = $exception;
        return $this;
    }
 function getErrors(): array
    {
        if (!$this->errors) {
            return [];
        }

        return $this->errors;
    }

    public function addError(array $error): CustomResponseInterface
    {
        $this->errors[] = $error;
        return $this;
    }

    public function getData(): mixed
    {
        if (!$this->data) {
            return [];
        }

        return $this->data;
    }

    public function setData(mixed $data): CustomResponseInterface
    {
        $this->data = $data;
        return $this;
    }
}