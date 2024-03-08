<?php
namespace StarreDEV\Framework\Interfaces;

interface CustomResponseInterface
{
    public function getJson(): string;
    public function getStatus(): string;
    public function setStatus(string $status): CustomResponseInterface;
    public function getCode(): int|string;
    public function setCode(int|string $code): CustomResponseInterface;
    public function getException(): string;
    public function setException(string $exception): CustomResponseInterface;
    public function getErrors(): array;
    public function addError(array $error): CustomResponseInterface;
    public function getData(): mixed;
    public function setData(mixed $data): CustomResponseInterface;
}