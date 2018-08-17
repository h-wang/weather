<?php

namespace Hongliang\Weather\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Hongliang\Weather\Model\Place;

class BaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('weather:base')
            ->setDescription('Not an actual command.');
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
