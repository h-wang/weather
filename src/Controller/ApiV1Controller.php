<?php

namespace Hongliang\Weather\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiV1Controller extends AbstractController
{
    private $doSimplifyResult = false;

    public function current(Request $request, $location)
    {
        $location = trim($location);
        $provider = $request->query->get('provider');

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        switch ($provider) {
            case 'owm':
            case 'openweathermap':
                $p = new \Hongliang\Weather\Provider\OpenWeatherMapProvider();
                $p->setApiKey($this->getParameter('openweathermap_api_key'));
                break;
            case 'heweather':
            default:
                $p = new \Hongliang\Weather\Provider\HeWeatherProvider();
                $p->setApiKey($this->getParameter('heweather_api_key'));
                break;
        }

        // try cache
        if (file_exists($cacheFile = $p->getCacheDir().'/'.date('Ymd').'_current_'.$location)) {
            $res = file_get_contents($cacheFile);
        } else {
            try {
                $res = $p->setPlaceByName($location)->getCurrent()->serialize();
            } catch (\Exception $e) {
                return $response->setStatusCode(Response::HTTP_BAD_REQUEST)
                    ->setContent(json_encode(['error' => $e->getMessage()]));
            }
        }
        $res = $this->attachAirQualityInfo($res);

        if ($this->doSimplifyResult) {
            $res = $this->simplifyResult($res);
        }

        return $response->setStatusCode(Response::HTTP_OK)->setContent($res);
    }

    private function attachAirQualityInfo($weather)
    {
        $w = json_decode($weather);
        $location = rtrim($w->city, '市').'市';
        $p = new \Hongliang\Weather\Provider\MeeProvider();
        $p->setApiKey([$this->getParameter('mee_username'), $this->getParameter('mee_password')]);

        $cacheFile = $p->getCacheDir().'/'.date('Ymd').'_aq';
        if (file_exists($cacheFile)) {
            $aq = json_decode(file_get_contents($cacheFile));
        } else {
            $aq = false;
            try {
                $aq = $p->getCurrent();
            } catch (\Throwable $th) {
            }
        }

        if ($aq) {
            if ($aq = $p->getLocationCurrent($location, $aq)) {
                $w->aqi = $aq->AQI;
                $w->pm10 = $aq->PM10;
                $w->pm2_5 = $aq->PM2_5;
                $w->aqi_time = $aq->TIMEPOINT;

                return json_encode($w);
            }
        }

        return $weather;
    }

    public function simple(Request $request, $location)
    {
        $this->doSimplifyResult = true;

        return $this->current($request, $location);
    }

    private function simplifyResult($jsonString)
    {
        $json = json_decode($jsonString);
        $o = [
            'city' => $json->city,
            'temperature' => $json->temperature,
            'minTemperature' => $json->minTemperature,
            'maxTemperature' => $json->maxTemperature,
            'humidity' => $json->humidity,
            'description' => $json->description,
            'windDirection' => $json->windDirection,
            'windSpeed' => $json->windSpeed,
            'windForce' => $json->windForce,
            'imageUrl' => $json->imageUrl,
            'image2Url' => $json->image2Url,
            'uv_index' => $json->uvIndex,
        ];
        if (property_exists($json, 'aqi')) {
            $o['aqi'] = $json->aqi;
            $o['aqi_time'] = $json->aqi_time;
            $o['pm10'] = $json->pm10;
            $o['pm2_5'] = $json->pm2_5;
        }
        foreach ($json->lifestyle as $ls) {
            if ('紫外线指数' == $ls->type) {
                // https://baike.baidu.com/item/%E7%B4%AB%E5%A4%96%E7%BA%BF%E6%8C%87%E6%95%B0/2044758
                $o['uv'] = $ls->title;
                $o['uv_description'] = $ls->description;
                continue;
            }
            if ('空气污染扩散条件指数' == $ls->type) {
                // 一级优，二级良，三级轻度污染，四级中度污染，直至五级重度污染，六级严重污染
                // https://wenku.baidu.com/view/2d791af704a1b0717fd5dd9a.html
                $o['air'] = $ls->title;
                $o['air_description'] = $ls->description;
                continue;
            }
        }

        return json_encode($o);
    }
}
