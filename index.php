<?php

require __DIR__ . '/vendor/autoload.php';

use App\Buoy;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use League\CLImate\CLImate;

const LOG_FILE = __DIR__ . '/tmp/Buoy.log';
const LATEST_X_HOURS = 8; // Calculate trend by the LATEST_X_HOURS data

// Configure system log
$buoyLog = new Logger('buoyLog');
$buoyLog->pushHandler(new StreamHandler(LOG_FILE));

// Invoke user selections of interested buoy stations
$allBuoys = [
    'C6AH2' => '富貴角浮標',
     '46694A' => '龍洞浮標',
     '46708A' => '龜山島浮標',
     '46761F' => '成功浮標',
     '46759A' => '鵝鑾鼻浮標',
     '46714D' => '小琉球浮標',
     'COMC08' => '彌陀浮標'
];
$CLI = new CLImate;
$CLI->clear();
$input = $CLI->checkboxes('選擇要分析的浮標資料: ', $allBuoys);
$buoys = $input->prompt();
if (count($buoys) > 0) {
    $CLI->yellow('正在取得浮標資料中, 請稍候...');
}

// Populate buoy data by iterating through all selected stations
for ($i = 0; $i < count($buoys); $i++) {
    try {
        $buoy = new Buoy($buoys[$i]);
        $CLI->lightGreen($buoy->getBuoyName());
        // 1.2.0: Use getBuoyReportArray(LATEST_X_HOURS) to get report in 
        // array so the report can be displayed by $CLI->table formation
        // $CLI->out($buoy->getBuoyReport(LATEST_X_HOURS));
        $CLI->table($buoy->getBuoyReportArray(LATEST_X_HOURS));
        $buoyLog->info('Successfully reported buoy info for ' . $buoy->getBuoyName(), array('buoyID' => $buoys[$i]));
    } catch (\Exception $e) {
        $CLI->red('錯誤: 無法取得浮標編號 ' . $buoys[$i] . ' 資料!');
        $buoyLog->error('Failed to connect to buoy data source. Error message: ' . $e->getMessage(), array('Script' => $e->getFile(), 'Line No.' => $e->getLine()));
    }
}
