<?php

namespace Hongliang\Weather\Provider;

use Hongliang\Weather\Model\Weather;
use Hongliang\Weather\Model\Lifestyle;
use Hongliang\Weather\Model\LifestyleType;

class HeWeatherProvider extends BaseProvider implements ProviderInterface
{
    private $apiKey;
    private $language = 'cn';
    private $apiUrl = 'https://free-api.heweather.com/s6/weather';
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

    public function getCurrent()
    {
        if (!$this->place || !$this->apiKey) {
            throw new \Exception('Place not set or no API key.');
        }
        $w = file_get_contents($this->apiUrl.'?location='.urlencode($this->place->getName()).'&key='.$this->apiKey);
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
            ->setUvIndex($w['daily_forecast'][0]['uv_index'])
            ->setSunrise($w['daily_forecast'][0]['sr'])
            ->setSunset($w['daily_forecast'][0]['ss'])
            ->setImageUrl($this->getImageUrl($w['daily_forecast'][0]['cond_code_d']))
            ;
        if ($w['daily_forecast'][0]['cond_code_d'] != $w['daily_forecast'][0]['cond_code_n']) {
            $weather->setDescription(
                $w['daily_forecast'][0]['cond_txt_d'].' - '.$w['daily_forecast'][0]['cond_txt_n']
            )->setImage2Url(
                $this->getImageUrl($w['daily_forecast'][0]['cond_code_n'])
            );
        }

        // lifestyle
        $lifestyle = new Lifestyle();
        $codes = $this->typeCodeToString();
        foreach ($w['lifestyle'] as $ls) {
            $lifestyle->addType(
                (new LifestyleType())
                    ->setType($codes[$ls['type']])
                    ->setTitle($ls['brf'])
                    ->setDescription($ls['txt'])
            );
        }
        $weather->setLifestyle($lifestyle);

        $this->cache($weather->serialize(), sprintf('%d_current_'.$this->place->getName(), date('Ymd')));

        return $weather;
    }

    private function getImageUrl($conditionCode)
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && 'off' != $_SERVER['HTTPS']) || 443 == $_SERVER['SERVER_PORT']) ? 'https://' : 'http://';

        return $protocol.$_SERVER['SERVER_NAME'].sprintf('/i/%s.png', $conditionCode);
    }

    protected $typeCodes = null;

    protected function typeCodeToString()
    {
        if (null === $this->typeCodes) {
            $this->typeCodes = [
                'comf' => '舒适度指数',
                'cw' => '洗车指数',
                'drsg' => '穿衣指数',
                'flu' => '感冒指数',
                'sport' => '运动指数',
                'trav' => '旅游指数',
                'uv' => '紫外线指数',
                'air' => '空气污染扩散条件指数',
                'ac' => '空调开启指数',
                'ag' => '过敏指数',
                'gl' => '太阳镜指数',
                'mu' => '化妆指数',
                'airc' => '晾晒指数',
                'ptfc' => '交通指数',
                'fisin' => '钓鱼指数',
                'spi' => '防晒指数',
            ];
        }

        return $this->typeCodes;
    }

    public function getForcast($days = 5)
    {
    }
}
