<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Command\Client;

use DateTime;
use Exception;
use OsrsGeApiClient\Command\ClientCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

use function floor;
use function sprintf;

use const PHP_EOL;

#[AsCommand(name: 'api:graph')]
final class GraphCommand extends ClientCommand
{
    protected function configure(): void
    {
        $this->addArgument('itemId', InputArgument::REQUIRED, 'ID of item to get graph for');
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $itemId = (int) $input->getArgument('itemId');

        try {
            $graph = $this->client->graph($itemId);

            $this->renderGraph($output, 'daily', $graph->daily);
            $this->renderGraph($output, 'average', $graph->average);

            return self::SUCCESS;
        } catch (Exception) {
            return self::FAILURE;
        }
    }

    /**
     * @throws Exception
     */
    private function renderGraph(OutputInterface $output, string $type, array $graph): void
    {
        $output->writeln(sprintf('%s: ', $type));

        $table = new Table($output);
        $table->setHeaders(['timestamp', 'price']);

        foreach ($graph as $timestamp => $price) {
            $date = new DateTime(sprintf('@%d', floor($timestamp / 1000)));
            $table->addRow([$date->format('Y-m-d'), $price]);
        }

        $table->render();
        echo PHP_EOL;
    }
}
