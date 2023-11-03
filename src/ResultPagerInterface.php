<?php

declare(strict_types=1);

namespace OsrsGeApiClient;

use Generator;
use Http\Client\Exception as HttpClientException;
use OsrsGeApiClient\Api\AbstractApi;

interface ResultPagerInterface
{
    /**
     * @throws HttpClientException
     */
    public function fetch(AbstractApi $api, string $method, array $parameters = []): array;

    /**
     * @throws HttpClientException
     */
    public function fetchAll(AbstractApi $api, string $method, array $parameters = []): array;

    /**
     * @throws HttpClientException
     */
    public function fetchAllLazy(AbstractApi $api, string $method, array $parameters = []): Generator;

    public function hasNext(): bool;

    /**
     * @throws HttpClientException
     */
    public function fetchNext(): array;

    public function hasPrevious(): bool;

    /**
     * @throws HttpClientException
     */
    public function fetchPrevious(): array;

    /**
     * @throws HttpClientException
     */
    public function fetchFirst(): array;

    /**
     * @throws HttpClientException
     */
    public function fetchLast(): array;
}
