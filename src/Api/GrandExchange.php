<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Api;

use Http\Client\Exception as HttpClientException;
use OsrsGeApiClient\Entity\Graph;
use OsrsGeApiClient\Entity\Item;
use OsrsGeApiClient\HttpClient\Util\Converter\JsonAlphas;
use OsrsGeApiClient\HttpClient\Util\Converter\JsonGraph;
use OsrsGeApiClient\HttpClient\Util\Converter\JsonItem;
use OsrsGeApiClient\HttpClient\Util\Converter\JsonItems;
use function sprintf;

class GrandExchange extends AbstractApi
{
    public const URL = 'm=itemdb_oldschool/api';

    /**
     * @throws HttpClientException
     */
    public function category(array $parameters = []): array
    {
        $resolver = $this->createOptionsResolver();
        $resolver->setDefined('category')
            ->setDefault('category', 1)
            ->setAllowedTypes('category', 'int');

        return JsonAlphas::decode(
            $this->get(sprintf('%s/catalogue/category.json', self::URL), $resolver->resolve($parameters))
        );
    }

    /**
     * @return Item[]
     *
     * @throws HttpClientException
     */
    public function items(string $alpha, array $parameters = []): array
    {
        $resolver = $this->createOptionsResolver();
        $resolver->setDefined('category')
            ->setDefault('category', 1)
            ->setAllowedTypes('category', 'int')
            ->setAllowedValues('category', function ($value): bool {
                return $value > 0;
            });
        $resolver->setDefined('alpha')
            ->setAllowedTypes('alpha', 'string');

        $parameters['alpha'] = $alpha;

        return JsonItems::decode(
            $this->get(sprintf('%s/catalogue/items.json', self::URL), $resolver->resolve($parameters)),
        );
    }

    /**
     * @throws HttpClientException
     */
    public function detail(int $id, array $parameters = []): Item
    {
        $resolver = $this->createOptionsResolver();
        $resolver->setDefined('item')
            ->setDefault('item', 1)
            ->setAllowedTypes('item', 'int')
            ->setAllowedValues('item', function ($value): bool {
                return $value > 0;
            });

        $parameters['item'] = $id;

        return JsonItem::decode(
            $this->get(sprintf('%s/catalogue/detail.json', self::URL), $resolver->resolve($parameters)),
        );
    }

    /**
     * @throws HttpClientException
     */
    public function graph(int $id, array $parameters = []): Graph
    {
        $resolver = $this->createOptionsResolver();

        return JsonGraph::decode(
            $this->get(sprintf('%s/graph/%d.json', self::URL, $id), $resolver->resolve($parameters))
        );
    }
}
