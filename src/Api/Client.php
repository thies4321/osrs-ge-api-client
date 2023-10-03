<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Api;

use OsrsGeApiClient\DTO\Api\AlphaCollection;
use OsrsGeApiClient\DTO\Api\Graph;
use OsrsGeApiClient\DTO\Api\Info;
use OsrsGeApiClient\DTO\Api\Item;
use OsrsGeApiClient\DTO\Api\ItemCollection;
use OsrsGeApiClient\Exception\Client\InvalidStatusCode;
use OsrsGeApiClient\Exception\ConfigException;
use OsrsGeApiClient\Exception\Transformer\InvalidJson;
use OsrsGeApiClient\Exception\Transformer\MissingData;
use OsrsGeApiClient\System\Config;
use OsrsGeApiClient\Transformer\DTO\AlphaCollectionTransformer;
use OsrsGeApiClient\Transformer\DTO\GraphTransformer;
use OsrsGeApiClient\Transformer\DTO\InfoTransformer;
use OsrsGeApiClient\Transformer\DTO\ItemCollectionTransformer;
use OsrsGeApiClient\Transformer\DTO\ItemTransformer;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function rtrim;
use function sprintf;

final readonly class Client
{
    public const DEFAULT_CATEGORY = 1;

    private Config $config;
    private HttpClientInterface $httpClient;

    /**
     * @throws ConfigException
     */
    public function __construct(?Config $config = null, ?HttpClientInterface $httpClient = null)
    {
        $this->config = $config ?? new Config();
        $this->httpClient = $httpClient ?? HttpClient::create(
            $this->config->get('client_options.defaultOptions', true),
            $this->config->get('client_options.maxHostConnections', true),
            $this->config->get('client_options.maxPendingPushes', true)
        );
    }

    /**
     * @throws ClientExceptionInterface
     * @throws ConfigException
     * @throws InvalidStatusCode
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws InvalidJson
     * @throws MissingData
     */
    public function info(): Info
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('%s/info.json', rtrim($this->config->get('base_url', true), '/'))
        );

        if ($response->getStatusCode() !== 200) {
            throw InvalidStatusCode::forStatusCode($response->getStatusCode());
        }

        return (new InfoTransformer)->jsonToDTO($response->getContent());
    }

    /**
     * @throws ClientExceptionInterface
     * @throws ConfigException
     * @throws InvalidJson
     * @throws InvalidStatusCode
     * @throws MissingData
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function category(): AlphaCollection
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('%s/catalogue/category.json', rtrim($this->config->get('base_url', true), '/')),
            [
                'query' => [
                    'category' => self::DEFAULT_CATEGORY,
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw InvalidStatusCode::forStatusCode($response->getStatusCode());
        }

        return (new AlphaCollectionTransformer)->jsonToDTO($response->getContent());
    }

    /**
     * @throws ClientExceptionInterface
     * @throws ConfigException
     * @throws InvalidJson
     * @throws InvalidStatusCode
     * @throws MissingData
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function items(string $alpha, int $page = 1): ItemCollection
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('%s/catalogue/items.json', rtrim($this->config->get('base_url', true), '/')),
            [
                'query' => [
                    'category' => self::DEFAULT_CATEGORY,
                    'alpha' => $alpha,
                    'page' => $page,
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw InvalidStatusCode::forStatusCode($response->getStatusCode());
        }

        return (new ItemCollectionTransformer)->jsonToDTO($response->getContent());
    }

    /**
     * @throws ClientExceptionInterface
     * @throws ConfigException
     * @throws InvalidJson
     * @throws InvalidStatusCode
     * @throws MissingData
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function detail(int $itemId): Item
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('%s/catalogue/detail.json', rtrim($this->config->get('base_url', true), '/')),
            [
                'query' => [
                    'item' => $itemId,
                ],
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw InvalidStatusCode::forStatusCode($response->getStatusCode());
        }

        return (new ItemTransformer)->jsonToDTO($response->getContent());
    }

    /**
     * @throws ClientExceptionInterface
     * @throws ConfigException
     * @throws InvalidJson
     * @throws InvalidStatusCode
     * @throws MissingData
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function graph(int $itemId): Graph
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('%s/graph/%d.json', rtrim($this->config->get('base_url', true), '/'), $itemId)
        );

        if ($response->getStatusCode() !== 200) {
            throw InvalidStatusCode::forStatusCode($response->getStatusCode());
        }

        return (new GraphTransformer)->jsonToDTO($response->getContent());
    }
}
