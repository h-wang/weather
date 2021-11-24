<?php

namespace Hongliang\Weather\Provider;

use Hongliang\Weather\Model\Lifestyle;
use Hongliang\Weather\Model\LifestyleType;
use Hongliang\Weather\Model\Weather;
use Symfony\Component\HttpClient\HttpClient;

class QWeatherProvider extends BaseProvider implements ProviderInterface
{
    private $apiKey;
    private $language = 'cn';
    private $apiUrl = 'https://devapi.qweather.com/v7/weather/now';
    private $forcaseApiUrl = 'https://devapi.qweather.com/v7/weather/3d';
    private $lifestyleApiUrl = 'https://devapi.qweather.com/v7/indices/1d';
    private $aqApiUrl = 'https://devapi.qweather.com/v7/air/now';
    private $geoApiUrl = 'https://geoapi.qweather.com/v2/city/lookup';
    // private $imageUrl = 'https://cdn.heweather.com/cond_icon/%s.png';

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

    protected function getHttpClient()
    {
        return HttpClient::create();
    }

    protected function getLocation($location)
    {
        $res = $this->getHttpClient()->request('GET', $this->geoApiUrl.'?location='.$location.'&key='.$this->apiKey);
        $w = $res->getContent();
        $w = json_decode($w, true);
        if ('200' != $w['code']) {
            throw new \Exception('Invalid location');
        }

        return $w['location'];
    }

    protected function getAirQuality($locationId)
    {
        $res = $this->getHttpClient()->request('GET', $this->aqApiUrl.'?location='.$locationId.'&key='.$this->apiKey);
        $w = $res->getContent();
        $w = json_decode($w, true);
        if ('200' != $w['code']) {
            throw new \Exception('Fetch air quality failed');
        }

        return $w['now'];
    }

    protected function getLifestyle($locationId)
    {
        $res = $this->getHttpClient()->request('GET', $this->lifestyleApiUrl.'?location='.$locationId.'&key='.$this->apiKey.'&type=0');
        $w = $res->getContent();
        $w = json_decode($w, true);
        if ('200' != $w['code']) {
            throw new \Exception('Fetch lifestyle failed');
        }

        return $w['daily'];
    }

    public function getCurrent()
    {
        if (!$this->place || !$this->apiKey) {
            throw new \Exception('Place not set or no API key.');
        }

        // get location info
        $location = $this->getLocation(urlencode($this->place->getName()));
        $location = $location[0];

        // get weather info
        $res = $this->getHttpClient()->request('GET', $this->forcaseApiUrl.'?location='.$location['id'].'&key='.$this->apiKey);
        $w = $res->getContent();
        $w = json_decode($w, true);
        
        if ('200' != $w['code']) {
            throw new \Exception('Weather fetch failed.');
        }

        $weather = new Weather();
        $weather->setCity($location['name'])
            ->setCountry($location['country'])
            ->setStamp(strtotime($w['updateTime']));

        $w = $w['daily'][0];

        $weather->setTemperature(floor(($w['tempMax']+$w['tempMin'])/2). ' ℃')
            ->setMinTemperature($w['tempMin'].' ℃')
            ->setMaxTemperature($w['tempMax'].' ℃')
            ->setPressure($w['pressure'].' hPa')
            ->setHumidity($w['humidity'].' %')
            ->setDescription($w['textDay'])
            ->setWindDirection($w['windDirDay'])
            ->setWindSpeed($w['windSpeedDay'].' km/h')
            ->setWindForce($w['windScaleDay'])
            ->setVisibility($w['vis'].' km')
            ->setUvIndex($w['uvIndex'])
            ->setSunrise($w['sunrise'])
            ->setSunset($w['sunset'])
            ->setImageUrl($this->getImageUrl($w['iconDay']))
            ;
        if ($w['textDay'] != $w['textNight']) {
            $weather->setDescription(
                $w['textDay'].' - '.$w['textNight']
            )->setImage2Url(
                $this->getImageUrl($w['iconNight'])
            );
        }
        
        // get lifestyle
        $lifestyle = new Lifestyle();
        $ls = $this->getLifestyle($location['id']);
        foreach ($ls as $s) {
            $lifestyle->addType(
                (new LifestyleType())
                    ->setType($s['name'])
                    ->setTitle($s['category'])
                    ->setDescription($s['text'])
            );
        }
        $weather->setLifestyle($lifestyle);

        // get air quality
        $aq = $this->getAirQuality($location['id']);
        $weather->setAqi($aq['aqi'])
            ->setPm10($aq['pm10'])
            ->setPm2p5($aq['pm2p5'])
            ->setO3($aq['o3'])
            ->setCo($aq['co'])
            ->setSo2($aq['so2'])
            ->setNo2($aq['no2'])
            ->setPrimaryPollutant($aq['primary'])
            ->setAqiTime(strtotime($aq['pubTime']));
        // $weather->aqi = $aq['aqi'];
        // $weather->pm10 = $aq['pm10'];
        // $weather->pm2_5 = $aq['pm2p5'];
        // $weather->o3 = $aq['o3'];
        // $weather->co = $aq['co'];
        // $weather->so2 = $aq['so2'];
        // $weather->no2 = $aq['no2'];
        // $weather->primary_pollutant = $aq['primary'];
        // $weather->aqi_time = strtotime($aq['pubTime']);

        $this->cache($weather->serialize(), sprintf('%d_current_'.$this->place->getName(), date('Ymd')));

        return $weather;
    }

    private function getImageUrl($conditionCode)
    {
        if (!isset($_SERVER['SERVER_PORT'])) {
            return null;
        }
        $protocol = ((!empty($_SERVER['HTTPS']) && 'off' != $_SERVER['HTTPS']) || 443 == $_SERVER['SERVER_PORT']) ? 'https://' : 'http://';

        return $protocol.$_SERVER['SERVER_NAME'].sprintf('/i/%s.png', $conditionCode);
    }

    public function getForcast($days = 5)
    {
    }
}
