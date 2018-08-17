<?php

namespace Hongliang\Weather\Formatter;

use Symfony\Component\Console\Output\OutputInterface;
use Hongliang\Weather\Model\Weather;

class ConsoleFormatter
{
    protected $output;
    protected $weather;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function setWeather(Weather $weather)
    {
        $this->weather = $weather;

        return $this;
    }

    public function format()
    {
        $output = $this->output;
        $weather = $this->weather;
        $output->writeln(
            '<comment>City: '.$weather->getCity().' - '.$weather->getCountry().'</comment>'
        );
        $output->writeln('<info>Current: '.$weather->getTemperature().'</info>');
        $output->writeln('<info>Min.: '.$weather->getMinTemperature().'</info>');
        $output->writeln('<info>Max.: '.$weather->getMaxTemperature().'</info>');
        $output->writeln(
            '<info>Pressure: '.$weather->getPressure().'</info>'
        );
        $output->writeln(
            '<info>Humidity: '.$weather->getHumidity().'</info>'
        );
        $output->writeln('<info>Description: '.$weather->getDescription().'</info>');
        $output->writeln('<info>Sunrise: '.$weather->getSunrise().'</info>');
        $output->writeln('<info>Sunset: '.$weather->getSunset().'</info>');
    }

    protected function normalizeChar($string)
    {
        return str_replace('&deg;C', '℃', $string);
    }
}
