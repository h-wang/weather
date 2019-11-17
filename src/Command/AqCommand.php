<?php

namespace Hongliang\Weather\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Hongliang\Weather\Formatter\ConsoleFormatter;
use Hongliang\Weather\Model\Weather;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AqCommand extends BaseCommand
{
    protected static $defaultName = 'weather:aq';
    protected $params = null;

    public function __construct(ParameterBagInterface $params)
    {
        parent::__construct();
        $this->params = $params;
    }

    protected function configure()
    {
        $this->setDescription('Get current air quality info.')
            ->setHelp('This command allows you to fetch current air quality info of cities in China')
            ->addArgument('location', InputArgument::REQUIRED, 'The location/city')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $location = trim($input->getArgument('location'));
        $location = rtrim($location, '市').'市';
        $p = new \Hongliang\Weather\Provider\MeeProvider();
        $p->setApiKey([$this->params->get('mee_username'), $this->params->get('mee_password')]);

        $cacheFile = $p->getCacheDir().'/'.date('Ymd').'_aq';
        if (file_exists($cacheFile)) {
            $aq = json_decode(file_get_contents($cacheFile));
        } else {
            $aq = $p->getCurrent();
        }
        $aq = $p->getLocationCurrent($location, $aq);

        if ($aq) {
            var_dump($aq);
        } else {
            $output->writeln('<error>Something\'s wrong</>');
        }
    }
}
