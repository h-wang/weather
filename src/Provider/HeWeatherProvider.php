<?php

namespace Hongliang\Weather\Provider;

use Hongliang\Weather\Model\Weather;

class HeWeatherProvider extends BaseProvider implements ProviderInterface
{
    private $apiKey;
    private $language = 'cn';
    private $apiUrl = 'https://free-api.heweather.com/s6/weather';
    private $imageUrl = 'https://cdn.heweather.com/cond_icon/%s.png';

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

        if ('ok' != $w['status']) {
            throw new \Exception('Invalid location');
        }

        $weather = new Weather();
        $weather->setCity($w['basic']['location'])
            ->setCountry($w['basic']['cnty'])
            ->setStamp((\DateTime::createFromFormat('Y-m-d H:i', $w['update']['loc']))->getTimestamp())
            ->setTemperature($w['now']['tmp'].' ℃')
            ->setMinTemperature($w['daily_forecast'][0]['tmp_min'].' ℃')
            ->setMaxTemperature($w['daily_forecast'][0]['tmp_max'].' ℃')
            ->setPressure($w['now']['pres'].' hPa')
            ->setHumidity($w['now']['hum'].' %')
            ->setDescription($w['daily_forecast'][0]['cond_txt_d'])
            ->setWindDirection($w['daily_forecast'][0]['wind_dir'])
            ->setWindSpeed($w['daily_forecast'][0]['wind_spd'].' km/h')
            ->setWindForce($w['daily_forecast'][0]['wind_sc'])
            ->setVisibility($w['daily_forecast'][0]['vis'].' km')
            ->setSunrise($w['daily_forecast'][0]['sr'])
            ->setSunset($w['daily_forecast'][0]['ss'])
            ->setImageUrl(sprintf($this->imageUrl, $w['daily_forecast'][0]['cond_code_d']))
            ;
        if ($w['daily_forecast'][0]['cond_code_d'] != $w['daily_forecast'][0]['cond_code_n']) {
            $weather->setDescription(
                $w['daily_forecast'][0]['cond_txt_d'].' - '.$w['daily_forecast'][0]['cond_txt_n']
            )->setImage2Url(
                sprintf($this->imageUrl, $w['daily_forecast'][0]['cond_code_n'])
            );
        }
        $this->cache($weather->serialize(), sprintf('%d_current_'.$this->place->getName(), date('Ymd')));

        return $weather;
    }

    public function getForcast($days = 5)
    {
    }
}
