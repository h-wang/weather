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
        $output->writeln('<info>AQI: '.$weather->getAqi().'</>');
        $output->writeln('<info>PM10: '.$weather->getPm10().'</>');
        $output->writeln('<info>PM2.5: '.$weather->getPm2p5().'</>');
        $output->writeln('<info>O3: '.$weather->getO3().'</>');
        $output->writeln('<info>CO: '.$weather->getCo().'</>');
        $output->writeln('<info>SO2: '.$weather->getSo2().'</>');
        $output->writeln('<info>NO2: '.$weather->getNo2().'</>');
        $output->writeln('<info>Primary pollutant: '.$weather->getPrimaryPollutant().'</>');
        if ($lifestyle = $weather->getLifestyle()) {
            $output->writeln('<comment>Lifestyle:</>');
            $lifestyle = (array) $lifestyle->getTypes();
            foreach ($lifestyle as $ls) {
                $output->writeln('<comment>'.$ls->toLongString().'</>');
            }
        }
    }

    protected function normalizeChar($string)
    {
        return str_replace('&deg;C', '℃', $string);
    }
}
