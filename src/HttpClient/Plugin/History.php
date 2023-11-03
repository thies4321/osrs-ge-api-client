<?php

declare(strict_types=1);

namespace OsrsGeApiClient\HttpClient\Plugin;

use Http\Client\Common\Plugin\Journal;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class History implements Journal
{
    private ?ResponseInterface $lastResponse;

    public function getLastResponse(): ?ResponseInterface
    {
        return $this->lastResponse;
    }

    public function addSuccess(RequestInterface $request, ResponseInterface $response): void
    {
        $this->lastResponse = $response;
    }

    public function addFailure(RequestInterface $request, ClientExceptionInterface $exception): void
    {
    }
}
