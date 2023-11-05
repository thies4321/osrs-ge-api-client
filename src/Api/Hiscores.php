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
    public function highscore(string $playerName, array $parameters = []): Hiscore
    {
        $resolver = $this->createOptionsResolver();
        $resolver->setDefined('player')
            ->setAllowedTypes('player', 'string');

        $parameters['player'] = $playerName;

        return TextHiscore::decode(
            $this->get(sprintf(self::URL, ''), $resolver->resolve($parameters))
        );
    }
}
