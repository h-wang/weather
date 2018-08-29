<?php

namespace Hongliang\Weather\Model;

class LifestyleType
{
    protected $type;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    protected $title;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

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

    public function toString($long = false)
    {
        return $long ? $this->toLongString() : $this->toShortString();
    }

    public function toShortString()
    {
        return $this->type.': '.$this->title;
    }

    public function toLongString()
    {
        return $this->type.': '.$this->title.' - '.$this->description;
    }

    public function toArray()
    {
        $t = (array) $this;
        $o = [
            'short' => $this->toShortString(),
            'long' => $this->toLongString(),
        ];
        foreach ($t as $key => $value) {
            $k = explode("\0", $key);
            $o[$k[2]] = $value;
        }

        return $o;
    }
}
