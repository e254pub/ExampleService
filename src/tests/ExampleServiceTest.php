<?php

namespace ExampleService\Tests;

use ExampleService\Dto\Comment;
use ExampleService\Dto\Request\AddCommentRequest;
use ExampleService\Dto\Request\GetCommentsRequest;
use ExampleService\Dto\Request\UpdateCommentRequest;
use ExampleService\ExampleService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class ExampleServiceTest extends TestCase
{
    public function getDataProvider(): array
    {
        return [
            [
                new Response(200, [], json_encode([
                        ['id' => 1, 'name' => 'Test', 'text' => 'test1'],
                        ['id' => 2, 'name' => 'Example', 'text' => 'text2']
                    ])
                ),
                [
                    new Comment(1, 'Test', 'test1'),
                    new Comment(2, 'Example', 'text2'),
                ]
            ],
        ];
    }

    public function addDataProvider(): array
    {
        $comment = new Comment(1, 'Test', 'test1');

        return [
            [
                new Response(201, [], json_encode(
                        $comment->toArray()
                    )
                ),
                $comment
            ],
        ];
    }

    public function updateDataProvider(): array
    {
        $comment = new Comment(1, 'Test', 'test1');

        return [
            [
                new Response(200, []),
                $comment,
                true
            ],
            [
                new Response(400, []),
                $comment,
                false
            ],
            [
                new Response(500, []),
                $comment,
                false
            ],
        ];
    }

    /**
     * @dataProvider getDataProvider
     * @param Response $response
     * @param array $comments
     * @return void
     */
    public function testGetComment(Response $response, array $comments): void
    {
        /** @var Client $stub */
        $stub = $this->createMock(Client::class);
        $stub->method('request')->willReturn($response);

        $logger = new NullLogger();
        $service = new ExampleService($stub, $logger);

        $request = new GetCommentsRequest();

        $this->assertEquals($comments, $service->getComments($request));
    }

    /**
     * @dataProvider addDataProvider
     * @param Response $response
     * @param Comment $exceptedComment
     * @return void
     */
    public function testAddComment(Response $response, Comment $exceptedComment): void
    {
        /** @var Client $stub */
        $stub = $this->createMock(Client::class);
        $stub->method('request')->willReturn($response);

        $logger = new NullLogger();
        $service = new ExampleService($stub, $logger);

        $comment = new Comment(null, $exceptedComment->getName(), $exceptedComment->getText());
        $request = new AddCommentRequest($comment);

        $this->assertEquals($exceptedComment, $service->addComment($request));
    }

    /**
     * @dataProvider updateDataProvider
     * @param Response $response
     * @param Comment $updateComment
     * @param bool $isUpdated
     * @return void
     */
    public function testUpdateComment(Response $response, Comment $updateComment, bool $isUpdated): void
    {
        /** @var Client $stub */
        $stub = $this->createMock(Client::class);
        $stub->method('request')->willReturn($response);

        $logger = new NullLogger();
        $service = new ExampleService($stub, $logger);

        $request = new UpdateCommentRequest($updateComment);

        $this->assertEquals($isUpdated, $service->updateComment($request));
    }
}
