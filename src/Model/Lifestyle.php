<?php

namespace Hongliang\Weather\Model;

class Lifestyle
{
    protected $types = null;

    public function getTypes()
    {
        return $this->types;
    }

    public function setTypes($types)
    {
        $this->types = $types;

        return $this;
    }

    public function addType(LifestyleType $type)
    {
        if (null === $this->types) {
            $this->types = [];
        }
        $this->types[] = $type;

        return $this;
    }

    public function toArray()
    {
        $o = [];
        foreach ((array) $this->types as $type) {
            $o[]= $type->toArray();
        }

        return $o;
    }
}
