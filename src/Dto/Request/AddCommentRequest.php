<?php

namespace ExampleService\Dto\Request;

use ExampleService\Dto\Comment;

class AddCommentRequest implements RequestInterface
{
    public function __construct(private Comment $comment) {
    }

    public function getUrl(): string {
        return '/comment';
    }

    public function getMethod(): string {
        return 'POST';
    }

    public function getBody(): ?array {
        return $this->comment->toArray();
    }

    public function getQuery(): ?array {
        return null;
    }
}
