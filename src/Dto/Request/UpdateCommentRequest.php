<?php

namespace ExampleService\Dto\Request;

use ExampleService\Dto\Comment;

class UpdateCommentRequest implements RequestInterface
{
    public function __construct(private Comment $comment) {
    }

    public function getUrl(): string {
        $id = $this->comment->getId();

        return "/comment/{$id}";
    }

    public function getMethod(): string {
        return 'PUT';
    }

    public function getBody(): ?array {
        return $this->comment->toArray();
    }

    public function getQuery(): ?array {
        return null;
    }
}
