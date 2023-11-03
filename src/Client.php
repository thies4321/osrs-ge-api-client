<?php

declare(strict_types=1);

namespace OsrsGeApiClient;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use OsrsGeApiClient\Api\GrandExchange;
use OsrsGeApiClient\HttpClient\Builder;
use OsrsGeApiClient\HttpClient\Plugin\History;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private const BASE_URL = 'https://secure.runescape.com';
    private const USER_AGENT = 'osrs-php-api-client/1.0';

    private Builder $httpClientBuilder;
    private History $responseHistory;

    public function __construct(Builder $httpClientBuilder = null)
    {
        $this->httpClientBuilder = $builder = $httpClientBuilder ?? new Builder();
        $this->responseHistory = new History();

        $builder->addPlugin(new HeaderDefaultsPlugin([
            'User-Agent' => self::USER_AGENT,
        ]));
        $builder->addPlugin(new RedirectPlugin());

        $this->setUrl(self::BASE_URL);
    }

    public function setUrl(string $url): void
    {
        $uri = $this->httpClientBuilder->getUriFactory()->createUri($url);

        $this->httpClientBuilder->removePlugin(AddHostPlugin::class);
        $this->httpClientBuilder->addPlugin(new AddHostPlugin($uri));
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->httpClientBuilder->getHttpClient();
    }

    public function getLastResponse(): ?ResponseInterface
    {
        return $this->responseHistory->getLastResponse();
    }

    public function grandExchange(): GrandExchange
    {

    }
}
