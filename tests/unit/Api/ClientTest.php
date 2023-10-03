<?php

declare(strict_types=1);

namespace OsrsGeApiClient\unit\Api;

use OsrsGeApiClient\Api\Client;
use OsrsGeApiClient\DTO\Api\Alpha;
use OsrsGeApiClient\DTO\Api\AlphaCollection;
use OsrsGeApiClient\DTO\Api\Info;
use OsrsGeApiClient\Entity\RuneDate;
use OsrsGeApiClient\Exception\Client\InvalidStatusCode;
use OsrsGeApiClient\Exception\ConfigException;
use OsrsGeApiClient\Exception\Transformer\InvalidJson;
use OsrsGeApiClient\Exception\Transformer\MissingData;
use OsrsGeApiClient\System\Config;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ClientTest extends TestCase
{
    private HttpClientInterface|MockObject $httpClient;
    private Client $client;

    /**
     * @throws ConfigException
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->client = new Client(
            new Config(['base_url' => 'https://example.com/m=itemdb_oldschool/api']),
            $this->httpClient
        );
    }

    /**
     * @throws InvalidStatusCode
     * @throws ConfigException
     * @throws InvalidJson
     * @throws MissingData
     * @throws Exception
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testInfoSuccessful(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);
        $response
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{"lastConfigUpdateRuneday":7749}');

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'https://example.com/m=itemdb_oldschool/api/info.json')
            ->willReturn($response);

        $runeDate = RuneDate::fromRuneDate(7749);
        $checkInfo = new Info($runeDate);

        $info = $this->client->info();

        $this->assertEquals($checkInfo, $info);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws ConfigException
     * @throws Exception
     * @throws InvalidJson
     * @throws InvalidStatusCode
     * @throws MissingData
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testInfoInvalidReturnCode(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->any())
            ->method('getStatusCode')
            ->willReturn(404);

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'https://example.com/m=itemdb_oldschool/api/info.json')
            ->willReturn($response);

        $this->expectException(InvalidStatusCode::class);

        $this->client->info();
    }

    /**
     * @return void
     * @throws ClientExceptionInterface
     * @throws ConfigException
     * @throws Exception
     * @throws InvalidJson
     * @throws InvalidStatusCode
     * @throws MissingData
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testCategorySuccessful(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);
        $response
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{"types":[],"alpha":[{"letter":"#","items":23},{"letter":"a","items":351},{"letter":"b","items":472},{"letter":"c","items":172},{"letter":"d","items":238},{"letter":"e","items":122},{"letter":"f","items":86},{"letter":"g","items":215},{"letter":"h","items":56},{"letter":"i","items":112},{"letter":"j","items":40},{"letter":"k","items":41},{"letter":"l","items":81},{"letter":"m","items":291},{"letter":"n","items":25},{"letter":"o","items":106},{"letter":"p","items":151},{"letter":"q","items":0},{"letter":"r","items":290},{"letter":"s","items":494},{"letter":"t","items":250},{"letter":"u","items":52},{"letter":"v","items":52},{"letter":"w","items":124},{"letter":"x","items":4},{"letter":"y","items":31},{"letter":"z","items":54}]}');

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'https://example.com/m=itemdb_oldschool/api/catalogue/category.json', ['query' => ['category' => Client::DEFAULT_CATEGORY,]])
            ->willReturn($response);


        $checkCategories = new AlphaCollection([
            new Alpha('#', 23),
            new Alpha('a', 351),
            new Alpha('b', 472),
            new Alpha('c', 172),
            new Alpha('d', 238),
            new Alpha('e', 122),
            new Alpha('f', 86),
            new Alpha('g', 215),
            new Alpha('h', 56),
            new Alpha('i', 112),
            new Alpha('j', 40),
            new Alpha('k', 41),
            new Alpha('l', 81),
            new Alpha('m', 291),
            new Alpha('n', 25),
            new Alpha('o', 106),
            new Alpha('p', 151),
            new Alpha('q', 0),
            new Alpha('r', 290),
            new Alpha('s', 494),
            new Alpha('t', 250),
            new Alpha('u', 52),
            new Alpha('v', 52),
            new Alpha('w', 124),
            new Alpha('x', 4),
            new Alpha('y', 31),
            new Alpha('z', 54)
        ]);
        $categories = $this->client->category();

        $this->assertEquals($checkCategories, $categories);
    }
}
