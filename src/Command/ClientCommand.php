<?php

declare(strict_types=1);

namespace OsrsGeApiClient\Command;

use OsrsGeApiClient\Api\Client;
use Symfony\Component\Console\Command\Command;

abstract class ClientCommand extends Command
{
    protected Client $client;

    public function __construct(string $name = null, ?Client $client = null)
    {
        parent::__construct($name);

        $this->client = $client ?? new Client();
    }
}
