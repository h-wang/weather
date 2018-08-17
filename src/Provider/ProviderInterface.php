<?php

namespace Hongliang\Weather\Provider;

use Hongliang\Weather\Model\Place;

interface ProviderInterface
{
    public function getPlace();

    public function setPlace(Place $place);

    public function getUnit();

    public function setUnit($unit);

    public function getCurrent();

    public function getForcast();
}
