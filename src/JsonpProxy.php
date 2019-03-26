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

/**
 * @author Chrudos Vorlicek <chrudos.vorlicek@gmail.com>
 */
class JsonpProxy
{

    /** @var string */
    private $requestUrl;

    /** @var string */
    private $callback;

    public function __construct(string $requestUrl, string $callback)
    {
        $this->requestUrl = $requestUrl;
        $this->callback = $callback;
    }

    public function sendRequest()
    {
        $client = new Client();
        $request = new Request('GET', $this->requestUrl . '&callback=' . $this->callback);
        $promise = $client->sendAsync($request)->then(function ($response) {
            $features = $this->processResponse($response->getBody());
            header('Content-type: application/json; charset=utf-8');
            header('Access-Control-Allow-Origin: *');

            echo Json::encode(['type' => 'FeatureCollection', 'features' => $features]);
        });
        $promise->wait();
    }

    /**
     * prepare features for geojson collection
     * @param string $body
     * @return array
     */
    private function processResponse(string $body): array
    {
        $geojsonResponse = [];
        // remove callback name from begining and ')' from end
        $cleanBody = Strings::before(Strings::after($body, "$this->callback("), ')', -1);
        foreach (Json::decode($cleanBody) as $type) {
            foreach ($type->featureList as $feature) {
                if($type->id === 'TL')  {
                    continue;
                }
                $geojsonResponse[] = $this->prepareFeature($feature, $type->id);
            }
        }
        return $geojsonResponse;
    }

    /**
     * format feature for frontend
     * @param object $feature
     * @param string $type
     * @return array
     * @throws Exception
     */
    private function prepareFeature(object $feature, string $type): array
    {
        $result = [
            'type' => 'Feature',
            'id' => $feature->id,
            'properties' => $feature->a
        ];
        $result['properties']['type'] = $type;
        switch ($type) {
            case 'Kamery':
            case 'Meteo':
            case 'PocasiOblast':
            case 'SjizdnostKomunikace':
            case 'TI':
            case 'TIU':
            case 'ZPI':
                $result['geometry'] = [
                    'type' => 'Point',
                    'coordinates' => [$feature->x, $feature->y]
                ];
                break;
            case 'TL':
                $result['geometry'] = [
                    'type' => 'Linestring',
                    'coordinates' => $feature->g
                ];
                break;
            default :
                print_r($feature);
                throw new Exception("Not implemented for type '$type'.");
        }
        return $result;
    }

}
