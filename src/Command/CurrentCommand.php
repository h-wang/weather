<?php

namespace Hongliang\Weather\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Hongliang\Weather\Formatter\ConsoleFormatter;
use Hongliang\Weather\Model\Weather;

class CurrentCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('weather:current')
            ->setDescription('Get current weather info.')
            ->setHelp('This command allows you to fetch current weather info of a location')
            ->addArgument('location', InputArgument::REQUIRED, 'The location/city')
            ->addOption(
                'provider',
                'p',
                InputOption::VALUE_OPTIONAL,
                'The weather service provider',
                'owm'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $location = trim($input->getArgument('location'));
        $provider = $input->getOption('provider');
        $current = null;

        switch ($provider) {
            case 'owm':
            case 'openweathermap':
                $p = new \Hongliang\Weather\Provider\OpenWeatherMapProvider();
                $p->setApiKey($this->getContainer()->getParameter('openweathermap_api_key'));
                break;
            case 'heweather':
            default:
                $p = new \Hongliang\Weather\Provider\HeWeatherProvider();
                $p->setApiKey($this->getContainer()->getParameter('heweather_api_key'));
                break;
        }

        $cacheFile = $p->getCacheDir().'/'.date('Ymd').'_current_'.$location;
        if (file_exists($cacheFile)) {
            $current = Weather::unserialize(file_get_contents($cacheFile));
        } else {
            $this->setLocation($location, $p);
            $current = $p->getCurrent();
        }

        if ($current) {
            $formatter = new ConsoleFormatter($output);
            $formatter->setWeather($current)->format();
        } else {
            $output->writeln(
                '<error>Something\'s wrong</>'
            );
        }
    }
}
