<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Command\Client;

use Exception;
use OsrsGeApiClient\Command\ClientCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(name: 'api:info')]
final class InfoCommand extends ClientCommand
{
    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $info = $this->client->info();

            $table = new Table($output);
            $table
                ->setVertical()
                ->setHeaders(['runedate', 'date'])
                ->setRows([
                [$info->lastConfigUpdateRuneday->toRuneDate(), $info->lastConfigUpdateRuneday->format('Y-m-d')],
            ]);
            $table->render();

            return self::SUCCESS;
        } catch (Exception) {
            return self::FAILURE;
        }
    }
}
