<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Api;

use Http\Client\Exception as HttpClientException;
use OsrsGeApiClient\Client;
use OsrsGeApiClient\HttpClient\Message\ResponseMediator;
use OsrsGeApiClient\HttpClient\Util\QueryStringBuilder;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_filter;
use function sprintf;
use function var_dump;

abstract class AbstractApi
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws HttpClientException
     */
    protected function getAsResponse(string $uri, array $params = [], array $headers = []): ResponseInterface
    {
        return $this->client->getHttpClient()->get(self::prepareUri($uri, $params), $headers);
    }

    /**
     * @throws HttpClientException
     */
    protected function get(string $uri, array $params = [], array $headers = []): array|string
    {
        $response = $this->getAsResponse($uri, $params, $headers);

        return ResponseMediator::getContent($response);
    }

    protected function createOptionsResolver(): OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined('page')
            ->setDefault('page', 1)
            ->setAllowedTypes('page', 'int')
            ->setAllowedValues('page', function ($value): bool {
                return $value > 0;
            })
        ;

        return $resolver;
    }

    private static function prepareUri(string $uri, array $query = []): string
    {
        $query = array_filter($query, function ($value): bool {
            return null !== $value;
        });

        return sprintf('%s%s', $uri, QueryStringBuilder::build($query));
    }
}
