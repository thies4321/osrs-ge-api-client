<?php

declare(strict_types=1);

namespace OsrsGeApiClient;

use Generator;
use Http\Client\Exception as HttpClientException;
use OsrsGeApiClient\Api\AbstractApi;

use OsrsGeApiClient\Exception\RuntimeException;
use OsrsGeApiClient\HttpClient\Message\ResponseMediator;

use function is_array;
use function iterator_to_array;

final class ResultPager implements ResultPagerInterface
{
    private Client $client;
    private array $pagination;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->pagination = [];
    }

    public function fetch(AbstractApi $api, string $method, array $parameters = []): array
    {
        $result = $api->$method(...$parameters);

        if (!is_array($result)) {
            throw new RuntimeException('Pagination of this endpoint is not supported.');
        }

        $this->postFetch();

        return $result;
    }

    public function fetchAll(AbstractApi $api, string $method, array $parameters = []): array
    {
        return iterator_to_array($this->fetchAllLazy($api, $method, $parameters));
    }

    public function fetchAllLazy(AbstractApi $api, string $method, array $parameters = []): Generator
    {
        foreach ($this->fetch($api, $method, $parameters) as $value) {
            yield $value;
        }

        while ($this->hasNext()) {
            foreach ($this->fetchNext() as $value) {
                yield $value;
            }
        }
    }

    public function hasNext(): bool
    {
        return isset($this->pagination['next']);
    }

    public function fetchNext(): array
    {
        return $this->get('next');
    }

    public function hasPrevious(): bool
    {
        return isset($this->pagination['prev']);
    }

    public function fetchPrevious(): array
    {
        return $this->get('prev');
    }

    public function fetchFirst(): array
    {
        return $this->get('first');
    }

    public function fetchLast(): array
    {
        return $this->get('last');
    }

    private function postFetch(): void
    {
        $response = $this->client->getLastResponse();

        $this->pagination = null === $response ? [] : ResponseMediator::getPagination($response);
    }

    /**
     * @throws HttpClientException
     */
    private function get(string $key): array
    {
        $pagination = $this->pagination[$key] ?? null;

        if (null === $pagination) {
            return [];
        }

        $result = $this->client->getHttpClient()->get($pagination);

        $content = ResponseMediator::getContent($result);

        if (! is_array($content)) {
            throw RuntimeException::forPaginationNotSupported();
        }

        $this->postFetch();

        return $content;
    }
}
