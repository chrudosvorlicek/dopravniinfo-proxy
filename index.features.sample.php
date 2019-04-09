<?php

require_once 'vendor/autoload.php';
require_once 'src/JsonpProxy.php';
require_once 'src/Polyline.php';
require_once 'src/FeatureProxy.php';
require_once 'src/LayerIds.php';

$testData = [
    ['id' => '2151$NDIC', 'layerId' => 'Kamery'],
    ['id' => '15', 'layerId' => 'Mereni'],
    ['id' => '11431', 'layerId' => 'PocasiOblast'],
    ['id' => '11432', 'layerId' => 'SjizdnostKomunikace'],
    ['id' => '5685960', 'layerId' => 'TI'],
    ['id' => '5601977', 'layerId' => 'TIU'],
    ['id' => '586073', 'layerId' => 'TL'],
    ['id' => '189$NDIC', 'layerId' => 'ZPI'],
    
];

$featureGetter = new \WfsProxy\FeatureProxy();

echo ' <body style="background:#c2c2d6;">';
foreach ($testData as $feature)
{
    echo $featureGetter->getFeature($feature['id'], $feature['layerId']);
}

echo '</body>';