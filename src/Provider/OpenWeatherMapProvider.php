<?php

namespace Hongliang\Weather\Provider;

use Hongliang\Weather\Model\Place;
use Hongliang\Weather\Model\Weather;
use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;

class OpenWeatherMapProvider extends BaseProvider implements ProviderInterface
{
    private $apiKey;
    private $language = 'en';

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

    private $handle;

    private function getHandle()
    {
        if (null === $this->handle) {
            // Get OpenWeatherMap object. Don't use caching (take a look into Example_Cache.php to see how it works).
            $this->handle = new OpenWeatherMap($this->apiKey);
        }

        return $this->handle;
    }

    public function getCurrent()
    {
        if (!$this->place || !$this->apiKey) {
            throw new \Exception('Place not set or no API key.');
        }
        $owm = $this->getHandle();
        // $w = $owm->getWeather($this->place->getName(), $this->unit, $this->language, $this->apiKey);

        try {
            $w = $owm->getWeather($this->place->getName(), $this->unit, $this->language);
        } catch (OWMException $e) {
            echo 'OpenWeatherMap exception: '.$e->getMessage().' (Code '.$e->getCode().').';
        } catch (\Exception $e) {
            echo 'General exception: '.$e->getMessage().' (Code '.$e->getCode().').';
        }

        $weather = new Weather();
        $weather->setCity($w->city->name)
            ->setCountry($w->city->country)
            ->setStamp()
            ->setTemperature((string) $w->temperature->now)
            ->setMinTemperature((string) $w->temperature->min)
            ->setMaxTemperature((string) $w->temperature->max)
            ->setPressure((string) $w->pressure)
            ->setHumidity((string) $w->humidity)
            ->setSunrise($w->sun->rise->format('H:i'))
            ->setSunset($w->sun->set->format('H:i'))
            ->setDescription((string) $w->weather->description)
            ->setWindDirection((string) $w->wind->direction)
            ->setWindSpeed((string) $w->wind->speed)
        ;
        $this->cache($weather->serialize(), sprintf('%d_current_'.$this->place->getName(), date('Ymd')));

        return $weather;
    }

    public function getForcast($days = 5)
    {
        $owm = $this->getHandle();
        $forecast = $owm->getWeatherForecast(
            $this->place->getName(),
            $this->unit,
            $this->language,
            $this->apiKey,
            $days
        );
    }
}
