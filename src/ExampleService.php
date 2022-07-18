<?php

namespace ExampleService;

use ExampleService\Dto\Request\GetCommentsRequest;
use ExampleService\Dto\Request\RequestInterface;
use ExampleService\Dto\Comment;
use ExampleService\Dto\Request\AddCommentRequest;
use ExampleService\Dto\Request\UpdateCommentRequest;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;
use Throwable;

class ExampleService
{
    /**
     * @param Client $guzzleClient
     * @param LoggerInterface $logger
     */
    public function __construct(
        private Client          $guzzleClient,
        private LoggerInterface $logger
    )
    {
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @param bool $returnResponseBodyOnly
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    protected function sendRequest(RequestInterface $request): Response
    {
        $options = [];

        if ($request->getBody() !== null) {
            $options['body'] = $request->getBody();
        }

        if ($request->getQuery() !== null) {
            $options['query'] = $request->getQuery();
        }

        return $this->guzzleClient->request($request->getMethod(), $request->getUrl(), $options);
    }

    /**
     * @param GetCommentsRequest $request
     * @return array
     */
    public function getComments(GetCommentsRequest $request): array
    {
        $commentDtos = [];

        try {
            /** @var Response $response */
            $response = $this->sendRequest($request);

            $content = $response->getBody()->getContents();
            $comments = json_decode($content);

            foreach ($comments as $comment) {
                $commentDtos[] = new Comment($comment->id, $comment->name, $comment->text);
            }
        } catch (Throwable $exception) {
            $this->logger->error('Example service get comments error', [
                'exception' => $exception,
            ]);
        }

        return $commentDtos;
    }

    /**
     * @param AddCommentRequest $request
     * @return Comment|null
     */
    public function addComment(AddCommentRequest $request): ?Comment
    {
        $commentObj = null;

        try {
            /** @var Response $response */
            $response = $this->sendRequest($request);

            $content = $response->getBody()->getContents();
            $comment = json_decode($content);

            $commentObj = new Comment($comment->id, $comment->name, $comment->text);
        } catch (Throwable $exception) {
            $this->logger->error('Example service add comment error', [
                'exception' => $exception,
            ]);
        }

        return $commentObj;
    }

    /**
     * @param UpdateCommentRequest $request
     * @return bool
     */
    public function updateComment(UpdateCommentRequest $request): bool
    {
        $isUpdate = false;

        try {
            /** @var Response $response */
            $response = $this->sendRequest($request);

            if ($response->getStatusCode() == 200) {
                $isUpdate = true;
            }
        } catch (Throwable $exception) {
            $this->logger->error('Example service update comment error', [
                'exception' => $exception,
            ]);
        }

        return $isUpdate;
    }
}
