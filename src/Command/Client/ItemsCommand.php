<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Command\Client;

use Exception;
use OsrsGeApiClient\Command\ClientCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function implode;
use function json_encode;
use function print_r;
use function sprintf;

#[AsCommand('api:items')]
final class ItemsCommand extends ClientCommand
{
    protected function configure()
    {
        $this
            ->addArgument('alpha', InputArgument::REQUIRED, 'The starting letter (lower case) for the items')
            ->addArgument('page', InputArgument::OPTIONAL, 'The page number', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $alpha = $input->getArgument('alpha');
            $page = (int) $input->getArgument('page');

            $itemCollection = $this->client->items($alpha, $page);

            $table = new Table($output);
            $table->setHeaders(['total'])->addRow([$itemCollection->total]);
            $table->render();

            $table = new Table($output);
            $table
                ->setVertical()
                ->setHeaders(['icon', 'icon_large', 'id', 'type', 'typeIcon', 'name', 'description', 'members', 'current', 'today']);
            foreach ($itemCollection->items as $item) {
                $table->addRow([
                    $item->icon,
                    $item->icon_large,
                    $item->id,
                    $item->type,
                    $item->typeIcon,
                    $item->name,
                    $item->description,
                    $item->members ? 'yes' : 'no',
                    json_encode($item->current),
                    json_encode($item->today)
                ]);
            }
            $table->render();

            return self::SUCCESS;
        } catch (Exception $exception) {
            return self::FAILURE;
        }
    }
}

//public string $icon;
//    public string $icon_large;
//    public int $id;
//    public string $type;
//    public string $typeIcon;
//    public string $name;
//    public string $description;
//    public bool $members;
//    public array $current;
//    public array $today;
