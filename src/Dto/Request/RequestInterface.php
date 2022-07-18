<?php

namespace ExampleService\Dto\Request;

interface RequestInterface
{
    public function getUrl(): string;

    public function getMethod(): string;

    public function getBody(): ?array;

    public function getQuery(): ?array;
}
