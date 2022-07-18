<?php

namespace ExampleService\Dto;

class Comment
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $text
    ) {
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    public function toArray(): array
    {
        $commentArray = [];

        if ($this->id !== null) {
            $commentArray['id'] = $this->id;
        }

        return array_merge($commentArray, [
            'name' => $this->name,
            'text' => $this->text,
        ]);
    }
}
