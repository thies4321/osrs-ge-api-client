<?php

declare(strict_types=1);

namespace OsrsGeApiClient\HttpClient\Message;

use OsrsGeApiClient\Exception\RuntimeException;
use OsrsGeApiClient\HttpClient\Util\JsonArray;
use Psr\Http\Message\ResponseInterface;

use function array_unique;
use function count;
use function explode;
use function implode;
use function in_array;
use function is_array;
use function is_int;
use function is_string;
use function preg_match;
use function sprintf;

final class ResponseMediator
{
    public const CONTENT_TYPE_HEADER = 'Content-Type';
    public const JSON_CONTENT_TYPE = 'application/json';
    public const STREAM_CONTENT_TYPE = 'application/octet-stream';
    public const MULTIPART_CONTENT_TYPE = 'multipart/form-data';

    public static function getContent(ResponseInterface $response): array|string
    {
        $body = (string) $response->getBody();

        if (!in_array($body, ['', 'null', 'true', 'false'], true) && str_starts_with($response->getHeaderLine(self::CONTENT_TYPE_HEADER), self::JSON_CONTENT_TYPE)) {
            return JsonArray::decode($body);
        }

        return $body;
    }

    public static function getPagination(ResponseInterface $response): array
    {
        $header = self::getHeader($response, 'Link');

        if (null === $header) {
            return [];
        }

        $pagination = [];
        foreach (explode(',', $header) as $link) {
            preg_match('/<(.*)>; rel="(.*)"/i', \trim($link, ','), $match);

            if (3 === count($match)) {
                $pagination[$match[2]] = $match[1];
            }
        }

        return $pagination;
    }

    private static function getHeader(ResponseInterface $response, string $name): ?string
    {
        $headers = $response->getHeader($name);

        return \array_shift($headers);
    }

    public static function getErrorMessage(ResponseInterface $response): ?string
    {
        try {
            $content = self::getContent($response);
        } catch (RuntimeException) {
            return null;
        }

        if (!is_array($content)) {
            return null;
        }

        if (isset($content['message'])) {
            $message = $content['message'];

            if (is_string($message)) {
                return $message;
            }

            if (is_array($message)) {
                return self::getMessageAsString($content['message']);
            }
        }

        if (isset($content['error_description'])) {
            $error = $content['error_description'];

            if (is_string($error)) {
                return $error;
            }
        }

        if (isset($content['error'])) {
            $error = $content['error'];

            if (is_string($error)) {
                return $error;
            }
        }

        return null;
    }

    /**
     * @param array $message
     *
     * @return string
     */
    private static function getMessageAsString(array $message): string
    {
        $format = '"%s" %s';
        $errors = [];

        foreach ($message as $field => $messages) {
            if (is_array($messages)) {
                $messages = array_unique($messages);
                foreach ($messages as $error) {
                    $errors[] = sprintf($format, $field, $error);
                }
            } elseif (is_int($field)) {
                $errors[] = $messages;
            } else {
                $errors[] = sprintf($format, $field, $messages);
            }
        }

        return implode(', ', $errors);
    }
}
