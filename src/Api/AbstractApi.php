<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Api;

use OsrsGeApiClient\Client;
use OsrsGeApiClient\HttpClient\Message\ResponseMediator;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractApi
{
    private const URI_PREFIX = '';

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function getAsResponse(string $uri, array $params = [], array $headers = []): ResponseInterface
    {
        return $this->client->getHttpClient()->get(self::prepareUri($uri, $params), $headers);
    }

    protected function get(string $uri, array $params = [], array $headers = []): array|string
    {
        $response = $this->getAsResponse($uri, $params, $headers);

        return ResponseMediator::getContent($response);
    }

    /**
     * @param int|string $uri
     *
     * @return string
     */
    protected static function encodePath($uri): string
    {
        return \rawurlencode((string) $uri);
    }

    /**
     * @param int|string $id
     * @param string     $uri
     *
     * @return string
     */
    protected function getProjectPath($id, string $uri): string
    {
        return 'projects/'.self::encodePath($id).'/'.$uri;
    }

    /**
     * Create a new OptionsResolver with page and per_page options.
     *
     * @return OptionsResolver
     */
    protected function createOptionsResolver(): OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined('page')
            ->setAllowedTypes('page', 'int')
            ->setAllowedValues('page', function ($value): bool {
                return $value > 0;
            })
        ;
        $resolver->setDefined('per_page')
            ->setAllowedTypes('per_page', 'int')
            ->setAllowedValues('per_page', function ($value): bool {
                return $value > 0 && $value <= 100;
            })
        ;

        return $resolver;
    }

    /**
     * Prepare the request URI.
     *
     * @param string $uri
     * @param array  $query
     *
     * @return string
     */
    private static function prepareUri(string $uri, array $query = []): string
    {
        $query = \array_filter($query, function ($value): bool {
            return null !== $value;
        });

        return \sprintf('%s%s%s', self::URI_PREFIX, $uri, QueryStringBuilder::build($query));
    }

    /**
     * Prepare the request URI.
     *
     * @param array<string,mixed>  $params
     * @param array<string,string> $files
     *
     * @return MultipartStreamBuilder
     */
    private function createMultipartStreamBuilder(array $params = [], array $files = []): MultipartStreamBuilder
    {
        $builder = new MultipartStreamBuilder($this->client->getStreamFactory());

        foreach ($params as $name => $value) {
            $builder->addResource($name, $value);
        }

        foreach ($files as $name => $file) {
            $builder->addResource($name, self::tryFopen($file, 'r'), [
                'headers' => [
                    ResponseMediator::CONTENT_TYPE_HEADER => self::guessFileContentType($file),
                ],
                'filename' => \basename($file),
            ]);
        }

        return $builder;
    }

    /**
     * Prepare the request multipart body.
     *
     * @param MultipartStreamBuilder $builder
     *
     * @return StreamInterface
     */
    private static function prepareMultipartBody(MultipartStreamBuilder $builder): StreamInterface
    {
        return $builder->build();
    }

    /**
     * Add the multipart content type to the headers if one is not already present.
     *
     * @param array<string,string>   $headers
     * @param MultipartStreamBuilder $builder
     *
     * @return array<string,string>
     */
    private static function addMultipartContentType(array $headers, MultipartStreamBuilder $builder): array
    {
        $contentType = \sprintf('%s; boundary=%s', ResponseMediator::MULTIPART_CONTENT_TYPE, $builder->getBoundary());

        return \array_merge([ResponseMediator::CONTENT_TYPE_HEADER => $contentType], $headers);
    }

    /**
     * Prepare the request JSON body.
     *
     * @param array<string,mixed> $params
     *
     * @return string|null
     */
    private static function prepareJsonBody(array $params): ?string
    {
        $params = \array_filter($params, function ($value): bool {
            return null !== $value;
        });

        if (0 === \count($params)) {
            return null;
        }

        return JsonArray::encode($params);
    }

    /**
     * Add the JSON content type to the headers if one is not already present.
     *
     * @param array<string,string> $headers
     *
     * @return array<string,string>
     */
    private static function addJsonContentType(array $headers): array
    {
        return \array_merge([ResponseMediator::CONTENT_TYPE_HEADER => ResponseMediator::JSON_CONTENT_TYPE], $headers);
    }

    /**
     * Safely opens a PHP stream resource using a filename.
     *
     * When fopen fails, PHP normally raises a warning. This function adds an
     * error handler that checks for errors and throws an exception instead.
     *
     * @param string $filename File to open
     * @param string $mode     Mode used to open the file
     *
     * @throws RuntimeException if the file cannot be opened
     *
     * @return resource
     *
     * @see https://github.com/guzzle/psr7/blob/1.6.1/src/functions.php#L287-L320
     */
    private static function tryFopen(string $filename, string $mode)
    {
        $ex = null;
        \set_error_handler(function () use ($filename, $mode, &$ex): void {
            $ex = new RuntimeException(\sprintf(
                'Unable to open %s using mode %s: %s',
                $filename,
                $mode,
                \func_get_args()[1]
            ));
        });

        $handle = \fopen($filename, $mode);
        \restore_error_handler();

        if (null !== $ex) {
            throw $ex;
        }

        /** @var resource */
        return $handle;
    }

    /**
     * Guess the content type of the file if possible.
     *
     * @param string $file
     *
     * @return string
     */
    private static function guessFileContentType(string $file): string
    {
        if (!\class_exists(\finfo::class, false)) {
            return ResponseMediator::STREAM_CONTENT_TYPE;
        }

        $finfo = new \finfo(\FILEINFO_MIME_TYPE);
        $type = $finfo->file($file);

        return false !== $type ? $type : ResponseMediator::STREAM_CONTENT_TYPE;
    }
}
