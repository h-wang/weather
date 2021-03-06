<?php

namespace Hongliang\Weather\Provider;

use Hongliang\Weather\Model\Place;

class BaseProvider implements ProviderInterface
{
    protected $place;
    protected $unit = 'metric';

    public function getPlace()
    {
        return $this->place;
    }

    public function setPlace(Place $place)
    {
        $this->place = $place;

        return $this;
    }

    public function setPlaceByName($name)
    {
        $place = new Place();
        $place->setName($name);
        $this->setPlace($place);

        return $this;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    public function getCurrent()
    {
    }

    public function getForcast()
    {
    }

    protected function getCacheBaseDir()
    {
        return __DIR__.'/../../var/cache';
    }

    public function getCacheDir()
    {
        $dir = $this->getCacheBaseDir().'/'.(new \ReflectionClass($this))->getShortName();
        if (!is_dir($dir)) {
            mkdir($dir, 0755);
        }

        return $dir;
    }

    protected function cache($content, $filename)
    {
        return file_put_contents($this->getCacheDir().'/'.$filename, $content);
    }
}
