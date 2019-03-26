<?php

require_once 'vendor/autoload.php';
require_once 'src/JsonpProxy.php';

\Tracy\Debugger::$maxDepth = 10;
\Tracy\Debugger::$maxLength = 1024;
//
//$url = 'http://www.dopravniinfo.cz/MapServices/DynamicLayers.ashx/GetFeatures?';
//$data = [
//    "resolution" => 305.748113140705,
//    "extent" => [
//        "xmin" => -938644.1826910313,
//        "xmax" => -497164.6949955026,
//        "ymin" => -1149791.3295778984,
//        "ymax" => -984410.2508981739
//    ],
//    "layers" => [
//        "TI",
//        "TIU",
//        "Kamery",
//        "Mereni",
//        "ZPI",
//        "Meteo",
//        "PocasiOblast",
//        "SjizdnostKomunikace",
//        "TL"
//    ],
//    "layerDefs" => [
//        "TI" => "(([MinZoom] is null) or ([MinZoom]>=1155581)) and ([PlatnostOd] <= '2019-03-25 23:59:59' AND [PlatnostDo] >= '2019-03-25 0:00')",
//        "TIU" => "(([MinZoom] is null) or ([MinZoom]>=1155581)) and ([PlatnostOd] <= '2019-03-25 23:59:59' AND [PlatnostDo] >= '2019-03-25 0:00')",
//        "TL" => "(([MinZoom] is null) or ([MinZoom]>=1155581))"
//    ]
//];
//$callback = 'map_jsonp_callback_97677';
//$requestUrl = $url . 'data=' . \Nette\Utils\Json::encode($data);

$requestUrl = filter_input(INPUT_GET, 'url');
$callback = filter_input(INPUT_GET, 'callback');

$proxy = new JsonpProxy($requestUrl, $callback);
$proxy->sendRequest();