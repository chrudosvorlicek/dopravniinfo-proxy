<?php

/*
 * This file is part of project JsonpProxy
 * Copyright (c) 2019 Chrudos Vorlicek
 * for more informations about license see LICENSE file
 */

namespace WfsProxy;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Nette\Utils\Json;
use Nette\Utils\Strings;
use WfsProxy\LayerIds;

/**
 * @author Chrudos Vorlicek <chrudos.vorlicek@gmail.com>
 */
class FeatureProxy
{

    const SERVICES_URL = 'http://www.dopravniinfo.cz/';
    const CAMERA_URL = 'DataServices/Camera.ashx?action=getkameradata&id=';
    const MAPTIP_URL = 'MapServices/Maptip.ashx?context=';
    const FEATURE_GEOM_URL = 'DataServices/FeatureGeometry.ashx';
    const MERENI_URL = 'DataServices/Mereni.ashx?action=getmerenidata&id=';

    /** @var string */
    private $lang;

    /**
     * @param string $lang
     */
    public function __construct(string $lang = 'cz')
    {
        $this->lang = $lang;
    }

    public function getFeature(string $id, string $layerId)
    {
        $data = [
            'id' => $id,
            'layerId' => $layerId,
            'lang' => $this->lang
        ];
        $body = $this->sendRequest($data);
        return $body;
        
    }

    public function sendRequest(array $data, string $type = self::MAPTIP_URL, string $method = 'GET')
    {
        $url = self::SERVICES_URL . $type . Json::encode($data);
        $client = new Client();
        $request = new Request($method, $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            $body = $response->getBody()->__toString();
            $body = Strings::replace($body, '/DataServices/', 'http://www.dopravniinfo.cz/DataServices');
            $body = Strings::replace($body, '/MapServices/', 'http://www.dopravniinfo.cz/MapServices');
            $body = Strings::replace($body, '/content/', 'http://www.dopravniinfo.cz/content');
            return $body;
        });
        return $promise->wait();
    }

}
