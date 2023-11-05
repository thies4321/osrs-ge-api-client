<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Api;

use Http\Client\Exception as HttpClientException;
use OsrsGeApiClient\Entity\RuneDate;
use OsrsGeApiClient\HttpClient\Util\Converter\JsonRuneDate;
use function sprintf;

class Configuration extends AbstractApi
{
    public const URL = 'm=itemdb_oldschool/api';

    /**
     * @throws HttpClientException
     */
    public function info(array $parameters = []): RuneDate
    {
        $resolver = $this->createOptionsResolver();

        return JsonRuneDate::decode(
            $this->get(sprintf('%s/info.json', self::URL), $resolver->resolve($parameters))
        );
    }
}
