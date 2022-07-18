<?php

namespace ExampleService\Dto\Request;

class GetCommentsRequest implements RequestInterface
{
    public function getUrl(): string {
        return '/comments';
    }

    public function getMethod(): string {
        return 'GET';
    }

    public function getBody(): ?array {
        return null;
    }

    public function getQuery(): ?array {
        return null;
    }
}
