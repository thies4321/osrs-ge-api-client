<?php

declare(strict_types=1);

namespace OsrsGeApiClient\HttpClient\Util\Converter;

use OsrsGeApiClient\Entity\Icons;
use OsrsGeApiClient\Entity\Item;
use OsrsGeApiClient\Entity\TradeHistory;
use function in_array;
use function is_numeric;
use function str_replace;
use function substr;

final class JsonItem
{
    private const AMOUNT_FORMAT_MAPPING = [
        'k' => 1000,
        'm' => 1000000,
        'b' => 1000000000,
    ];

    public static function decode(string $json): Item
    {
        $item = JsonArray::decode($json);

        if (isset($item['item'])) {
            $item = $item['item'];
        }

        $icons = new Icons(
            $item['icon'],
            $item['icon_large'],
            $item['typeIcon'],
        );

        $tradeHistory = new TradeHistory(
            self::processTradeHistoryItem($item['current']),
            self::processTradeHistoryItem($item['today']),
            isset($item['day30']) ? self::processTradeHistoryItem($item['day30']) : null,
            isset($item['day90']) ? self::processTradeHistoryItem($item['day90']) : null,
            isset($item['day180']) ? self::processTradeHistoryItem($item['day180']) : null,
        );

        return new Item(
            $item['id'],
            $item['type'],
            $item['name'],
            $item['description'],
            $item['members'] === 'true',
            $icons,
            $tradeHistory
        );
    }

    private static function processTradeHistoryItem(array $item): array
    {
        if (isset($item['price'])) {
            $price = str_replace(',', '', str_replace(' ', '', (string) $item['price']));
            $negative = str_starts_with($price, '-');

            if ($negative === true) {
                $price = substr($price, 1);
            }

            $amountFormat = substr($price, -1);

            if (in_array($amountFormat, ['k', 'm', 'b'])) {
                $multiplier = self::AMOUNT_FORMAT_MAPPING[$amountFormat];

                $price = (int) (((float) $price) * $multiplier);

                if ($negative === true) {
                    $price = 0 - $price;
                }

                $item['price'] = $price;
            }

            if (is_numeric($price)) {
                if ($negative === true) {
                    $price = 0 - (int) $price;
                }

                $item['price'] = (int) $price;
            }
        }

        if (isset($item['change'])) {
            $negative = str_starts_with($item['change'], '-');
            $change = (float) substr($item['change'], 1, -1);

            if ($negative === true) {
                $change = (0 - $change);
            }

            $item['change'] = $change;
        }

        return $item;
    }
}
