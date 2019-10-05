<?php

namespace Hongliang\Weather\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Hongliang\Weather\Formatter\ConsoleFormatter;
use Hongliang\Weather\Model\Weather;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CurrentCommand extends BaseCommand
{
    protected static $defaultName = 'weather:current';
    protected $params = null;

    public function __construct(ParameterBagInterface $params)
    {
        parent::__construct();
        $this->params = $params;
    }

    protected function configure()
    {
        $this->setDescription('Get current weather info.')
            ->setHelp('This command allows you to fetch current weather info of a location')
            ->addArgument('location', InputArgument::REQUIRED, 'The location/city')
            ->addOption(
                'provider',
                'p',
                InputOption::VALUE_OPTIONAL,
                'The weather service provider',
                'heweather'
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
                $p->setApiKey($this->params->get('openweathermap_api_key'));
                break;
            case 'heweather':
            default:
                $p = new \Hongliang\Weather\Provider\HeWeatherProvider();
                $p->setApiKey($this->params->get('heweather_api_key'));
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
            (new ConsoleFormatter($output))->setWeather($current)->format();
        } else {
            $output->writeln('<error>Something\'s wrong</>');
        }
    }
}
