<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Api;

use Http\Client\Exception as HttpClientException;
use OsrsGeApiClient\Entity\Hiscore;
use OsrsGeApiClient\HttpClient\Util\Converter\TextHiscore;

use function sprintf;

class Hiscores extends AbstractApi
{
    public const URL = 'm=hiscore_oldschool%s/index_lite.ws';

    /**
     * @throws HttpClientException
     */
    public function hiscore(string $playerName, array $parameters = [], string $slug = ''): Hiscore
    {
        $resolver = $this->createOptionsResolver();
        $resolver->setDefined('player')
            ->setAllowedTypes('player', 'string');

        $parameters['player'] = $playerName;

        return TextHiscore::decode(
            $this->get(sprintf(self::URL, $slug), $resolver->resolve($parameters))
        );
    }

    /**
     * @throws HttpClientException
     */
    public function ironman(string $playerName, array $parameters = []): Hiscore
    {
        return $this->hiscore($playerName, $parameters, '_ironman');
    }

    /**
     * @throws HttpClientException
     */
    public function hardcoreIronman(string $playerName, array $parameters = []): Hiscore
    {
        return $this->hiscore($playerName, $parameters, '_hardcore_ironman');
    }

    /**
     * @throws HttpClientException
     */
    public function ultimateIronman(string $playerName, array $parameters = []): Hiscore
    {
        return $this->hiscore($playerName, $parameters, '_ultimate');
    }

    /**
     * @throws HttpClientException
     */
    public function deadmanMode(string $playerName, array $parameters = []): Hiscore
    {
        return $this->hiscore($playerName, $parameters, '_deadman');
    }

    /**
     * @throws HttpClientException
     */
    public function seasonal(string $playerName, array $parameters = []): Hiscore
    {
        return $this->hiscore($playerName, $parameters, '_seasonal');
    }

    /**
     * @throws HttpClientException
     */
    public function tournament(string $playerName, array $parameters = []): Hiscore
    {
        return $this->hiscore($playerName, $parameters, '_tournament');
    }

    /**
     * @throws HttpClientException
     */
    public function freshStart(string $playerName, array $parameters = []): Hiscore
    {
        return $this->hiscore($playerName, $parameters, '_fresh_start');
    }
}
