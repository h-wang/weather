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
        $output->writeln('<info>Current: '.$weather->getTemperature().'</>');
        $output->writeln('<info>Min.: '.$weather->getMinTemperature().'</>');
        $output->writeln('<info>Max.: '.$weather->getMaxTemperature().'</>');
        $output->writeln('<info>Wind direction.: '.$weather->getWindDirection().'</>');
        $output->writeln('<info>Wind speed: '.$weather->getWindSpeed().'</>');
        $output->writeln('<info>Wind force: '.$weather->getWindForce().'</>');
        $output->writeln('<info>Visibility: '.$weather->getVisibility().'</>');
        $output->writeln(
            '<info>Pressure: '.$weather->getPressure().'</>'
        );
        $output->writeln(
            '<info>Humidity: '.$weather->getHumidity().'</>'
        );
        $output->writeln('<info>Description: '.$weather->getDescription().'</>');
        $output->writeln('<info>Sunrise: '.$weather->getSunrise().'</>');
        $output->writeln('<info>Sunset: '.$weather->getSunset().'</>');
    }

    protected function normalizeChar($string)
    {
        return str_replace('&deg;C', '℃', $string);
    }
}
