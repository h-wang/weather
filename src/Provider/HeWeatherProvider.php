<?php

namespace Hongliang\Weather\Provider;

use Hongliang\Weather\Model\Place;
use Hongliang\Weather\Model\Weather;

class HeWeatherProvider extends BaseProvider implements ProviderInterface
{
    private $apiKey;
    private $language = 'cn';
    private $apiUrl = 'https://free-api.heweather.com/s6/weather/now';

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    public function getCurrent()
    {
        if (!$this->place || !$this->apiKey) {
            throw new \Exception('Place not set or no API key.');
        }
        $w = file_get_contents($this->apiUrl.'?location='.$this->place->getName().'&key='.$this->apiKey);
        $w = json_decode($w, true);
        $w = $w['HeWeather6'][0];

        $weather = new Weather();
        $weather->setCity($w['basic']['location'])
            ->setCountry($w['basic']['cnty'])
            ->setStamp((\DateTime::createFromFormat("Y-m-d H:i", $w['update']['loc']))->getTimestamp())
            ->setTemperature($w['now']['tmp'].' ℃')
            ->setMinTemperature($w['now']['tmp'].' ℃')
            ->setMaxTemperature($w['now']['tmp'].' ℃')
            ->setPressure($w['now']['pres'].' hPa')
            ->setHumidity($w['now']['hum'].' %')
            ->setDescription($w['now']['cond_txt'])
        ;

        return $weather;
    }

    public function getForcast($days = 5)
    {
    }
}
