<?php

namespace Hongliang\Weather\Model;

class Weather
{
    protected $city;

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    protected $country;

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    protected $stamp;

    public function getStamp()
    {
        return $this->stamp;
    }

    public function setStamp($stamp = null)
    {
        $this->stamp = $stamp ?: time();

        return $this;
    }

    protected $temperature;

    public function getTemperature()
    {
        return $this->temperature;
    }

    public function setTemperature($temperature)
    {
        $this->temperature = $this->fixTemperatureUnitString($temperature);

        return $this;
    }

    protected $minTemperature;

    public function getMinTemperature()
    {
        return $this->minTemperature;
    }

    public function setMinTemperature($minTemperature)
    {
        $this->minTemperature = $this->fixTemperatureUnitString($minTemperature);

        return $this;
    }

    protected $maxTemperature;

    public function getMaxTemperature()
    {
        return $this->maxTemperature;
    }

    public function setMaxTemperature($maxTemperature)
    {
        $this->maxTemperature = $this->fixTemperatureUnitString($maxTemperature);

        return $this;
    }

    private function fixTemperatureUnitString($v)
    {
        return str_replace('&deg;C', '℃', $v);
    }

    protected $pressure;

    public function getPressure()
    {
        return $this->pressure;
    }

    public function setPressure($pressure)
    {
        $this->pressure = $pressure;

        return $this;
    }

    protected $humidity;

    public function getHumidity()
    {
        return $this->humidity;
    }

    public function setHumidity($humidity)
    {
        $this->humidity = $humidity;

        return $this;
    }

    protected $sunrise;

    public function getSunrise()
    {
        return $this->sunrise;
    }

    public function setSunrise($sunrise)
    {
        $this->sunrise = $sunrise;

        return $this;
    }

    protected $sunset;

    public function getSunset()
    {
        return $this->sunset;
    }

    public function setSunset($sunset)
    {
        $this->sunset = $sunset;

        return $this;
    }

    protected $windDirection;

    public function getWindDirection()
    {
        return $this->windDirection;
    }

    public function setWindDirection($windDirection)
    {
        $this->windDirection = $windDirection;

        return $this;
    }

    protected $windSpeed;

    public function getWindSpeed()
    {
        return $this->windSpeed;
    }

    public function setWindSpeed($windSpeed)
    {
        $this->windSpeed = $windSpeed;

        return $this;
    }

    protected $windForce;

    public function getWindForce()
    {
        return $this->windForce;
    }

    public function setWindForce($windForce)
    {
        $this->windForce = $windForce;

        return $this;
    }

    protected $visibility;

    public function getVisibility()
    {
        return $this->visibility;
    }

    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    protected $uvIndex;

    public function getUvIndex()
    {
        return $this->uvIndex;
    }

    public function setUvIndex($uvIndex)
    {
        $this->uvIndex = $uvIndex;

        return $this;
    }


    protected $description;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    protected $lifestyle;

    public function getLifestyle()
    {
        return $this->lifestyle;
    }

    public function setLifestyle($lifestyle)
    {
        $this->lifestyle = $lifestyle;

        return $this;
    }

    protected $imageUrl;

    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    protected $image2Url;

    public function getImage2Url()
    {
        return $this->image2Url;
    }

    public function setImage2Url($image2Url)
    {
        $this->image2Url = $image2Url;

        return $this;
    }

    protected $aqi;

    public function getAqi()
    {
        return $this->aqi;
    }

    public function setAqi($aqi)
    {
        $this->aqi = $aqi;

        return $this;
    }

    protected $pm10;

    public function getPm10()
    {
        return $this->pm10;
    }

    public function setPm10($pm10)
    {
        $this->pm10 = $pm10;

        return $this;
    }

    protected $pm2p5;

    public function getPm2p5()
    {
        return $this->pm2p5;
    }

    public function setPm2p5($pm2p5)
    {
        $this->pm2p5 = $pm2p5;

        return $this;
    }

    protected $o3;

    public function getO3()
    {
        return $this->o3;
    }

    public function setO3($o3)
    {
        $this->o3 = $o3;

        return $this;
    }

    protected $co;

    public function getCo()
    {
        return $this->co;
    }

    public function setCo($co)
    {
        $this->co = $co;

        return $this;
    }

    protected $so2;

    public function getSo2()
    {
        return $this->so2;
    }

    public function setSo2($so2)
    {
        $this->so2 = $so2;

        return $this;
    }

    protected $no2;

    public function getNo2()
    {
        return $this->no2;
    }

    public function setNo2($no2)
    {
        $this->no2 = $no2;

        return $this;
    }

    protected $primaryPollutant;

    public function getPrimaryPollutant()
    {
        return $this->primaryPollutant;
    }

    public function setPrimaryPollutant($primaryPollutant)
    {
        $this->primaryPollutant = $primaryPollutant;

        return $this;
    }

    protected $aqiTime;

    public function getAqiTime()
    {
        return $this->aqiTime;
    }

    public function setAqiTime($aqiTime)
    {
        $this->aqiTime = $aqiTime;

        return $this;
    }

    public function serialize()
    {
        $t = (array) $this;
        $o = [];
        foreach ($t as $key => $value) {
            $k = explode("\0", $key);
            $o[$k[2]] = is_object($value) ? $value->toArray() : $value;
        }

        return json_encode($o, JSON_UNESCAPED_UNICODE, 5);
    }

    public static function unserialize($string)
    {
        $o = json_decode($string, JSON_UNESCAPED_UNICODE);

        $me = new self();
        $me->setCity($o['city'])
            ->setCountry($o['country'])
            ->setStamp($o['stamp'])
            ->setTemperature($o['temperature'])
            ->setMinTemperature($o['minTemperature'])
            ->setMaxTemperature($o['maxTemperature'])
            ->setPressure($o['pressure'])
            ->setHumidity($o['humidity'])
            ->setSunrise($o['sunrise'])
            ->setSunset($o['sunset'])
            ->setWindDirection($o['windDirection'])
            ->setWindSpeed($o['windSpeed'])
            ->setWindForce($o['windForce'])
            ->setVisibility($o['visibility'])
            ->setDescription($o['description'])
            ->setImageUrl($o['imageUrl'])
            ->setImage2Url($o['image2Url'])
        ;
        if (isset($o['aqi'])) {
            $me->setAqi($o['aqi'])
                ->setPm10($o['pm10'])
                ->setPm2p5($o['pm2p5'])
                ->setO3($o['o3'])
                ->setCo($o['co'])
                ->setSo2($o['so2'])
                ->setNo2($o['no2'])
                ->setPrimaryPollutant($o['primaryPollutant'])
                ->setAqiTime($o['aqiTime']);
        }

        // lifestyle
        if ($o['lifestyle']) {
            $lifestyle = new Lifestyle();
            foreach ((array) $o['lifestyle'] as $ls) {
                $lifestyle->addType(
                    (new LifestyleType())
                        ->setType($ls['type'])
                        ->setTitle($ls['title'])
                        ->setDescription($ls['description'])
                );
            }
            $me->setLifestyle($lifestyle);
        }

        return $me;
    }
}
