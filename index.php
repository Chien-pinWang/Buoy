<?php

require __DIR__ . '/vendor/autoload.php';

use App\Buoy;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

const LOG_FILE = __DIR__ . '/tmp/Buoy.log';
const LATEST_X_HOURS = 8;

$northernBuoys = ['C6AH2', '46694A', '46708A'];
$southernBuoys = ['46761F', '46759A', '46714D', 'COMC08'];

$buoys = $northernBuoys;

if ($argc > 1 && strtoupper($argv[1]) == 'S') {
    $buoys = $southernBuoys;
}

$buoyLog = new Logger('buoyLog');
$buoyLog->pushHandler(new StreamHandler(LOG_FILE));

for ($i = 0; $i < count($buoys); $i++) {
    try {
        $buoy = new Buoy($buoys[$i]);
        echo $buoy->getBuoyName() . PHP_EOL;
        echo $buoy->getBuoyReport(LATEST_X_HOURS) . PHP_EOL;
        $buoyLog->info('Successfully reported buoy info for ' . $buoy->getBuoyName(), array('buoyID' => $buoys[$i]));
        // print_r($buoy->getStats(8, 'recWaveHeight'));
    } catch (\Exception $e) {
        echo '錯誤! 無法取得浮標編號 ' . $buoys[$i] . ' 資料.' . PHP_EOL . PHP_EOL;
        $buoyLog->error('Failed to connect to buoy data source. Error message: ' . $e->getMessage(), array('Script' => $e->getFile(), 'Line No.' => $e->getLine()));
    }
}
