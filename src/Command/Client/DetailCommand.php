<?php

namespace OsrsGeApiClient\Command\Client;

use Exception;
use OsrsGeApiClient\Command\ClientCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function json_encode;

#[AsCommand(name: 'api:detail')]
final class DetailCommand extends ClientCommand
{
    protected function configure(): void
    {
        $this->addArgument('itemId', InputArgument::REQUIRED, 'ID of item to get details for');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $itemId = (int) $input->getArgument('itemId');

        try {
            $item = $this->client->detail($itemId);

            $table = new Table($output);
            $table
                ->setVertical()
                ->setHeaders([
                    'icon',
                    'icon_large',
                    'id',
                    'type',
                    'typeIcon',
                    'name',
                    'description',
                    'members',
                    'current',
                    'today',
                    'day30',
                    'day90',
                    'day180',
                ])->addRow([
                    $item->icon,
                    $item->icon_large,
                    $item->id,
                    $item->type,
                    $item->typeIcon,
                    $item->name,
                    $item->description,
                    $item->members ? 'yes' : 'no',
                    json_encode($item->current),
                    json_encode($item->today),
                    json_encode($item->day30),
                    json_encode($item->day90),
                    json_encode($item->day180)
                ]);
            $table->render();

            return self::SUCCESS;
        } catch (Exception) {
            return self::FAILURE;
        }
    }
}
