<?php

require_once 'vendor/autoload.php';
require_once 'src/JsonpProxy.php';
require_once 'src/Polyline.php';

$requestUrl = filter_input(INPUT_GET, 'url');
$callback = filter_input(INPUT_GET, 'callback');

$proxy = new WfsProxy\JsonpProxy($requestUrl, $callback);
$proxy->sendRequest();