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

    public function serialize()
    {
        $t = (array) $this;
        $o = array();
        foreach ($t as $key => $value) {
            $k = explode("\0", $key);
            $o[$k[2]] = $value;
        }

        return json_encode($o, JSON_UNESCAPED_UNICODE);
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
            ->setDescription($o['description']);

        return $me;
    }
}
