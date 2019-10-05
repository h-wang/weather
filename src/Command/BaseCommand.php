<?php

namespace Hongliang\Weather\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommand extends Command
{
    protected static $defaultName = 'weather:base';

    protected function configure()
    {
        $this->setDescription('Not an actual command.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        throw new \Exception('Execution must be defined in actual class');
    }

    protected function setLocation($location, &$provider)
    {
        $provider->setPlaceByName($location);
    }
}
