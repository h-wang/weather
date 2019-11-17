<?php

namespace Hongliang\Weather\Provider;

use Hongliang\Weather\Model\Weather;

class MeeProvider extends BaseProvider implements ProviderInterface
{
    private $username;
    private $password;
    // private $apiUrl = 'http://datacenter.mee.gov.cn/websjzx/api/api/air/getAirDays.vm?';
    private $apiUrl = 'http://datacenter.mee.gov.cn/websjzx/api/api/air/getAirHours.vm?';

    public function setApiKey($cred)
    {
        $this->username = $cred[0];
        $this->password = $cred[1];

        return $this;
    }

    public function getCurrent()
    {
        $w = null;
        $cred = sprintf('Authorization: Basic %s', base64_encode($this->username.':'.$this->password));
        try {
            $w = file_get_contents(
                $this->apiUrl,
                false,
                stream_context_create(['http' => [
                    'method' => 'GET',
                    'header' => $cred,
                ]])
            );
            $w = json_decode(json_encode(simplexml_load_string($w, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
            $w = $w['TEnvAutoCtiyHourAqi'];
            $w = json_encode($w);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if ($w) {
            $this->cache($w, sprintf('%d_aq', date('Ymd')));
        }

        return $w;
    }

    public function getLocationCurrent($location, $current)
    {
        if (is_string($current)) {
            $current = json_decode($current);
        }
        foreach ((array) $current as $a) {
            if ($a->AREA == $location) {
                return $a;
            }
        }

        return false;
    }
}
