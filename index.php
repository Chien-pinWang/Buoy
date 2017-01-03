<?php

require __DIR__ . '/vendor/autoload.php';

use App\Buoy;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use League\CLImate\CLImate;

const LOG_FILE = __DIR__ . '/tmp/Buoy.log';
const LATEST_X_HOURS = 8; // Calculate trend by the LATEST_X_HOURS data
const VERBOSE_LEVEL = 2; // Report verbose level, 0 ~ 3

// Configure system log
$buoyLog = new Logger('buoyLog');
$buoyLog->pushHandler(new StreamHandler(LOG_FILE));

// Invoke user selections of interested buoy stations

function getUserInput(CLImate $CLI) {
    $allBuoys = [
        '富貴角浮標' => 'C6AH2',
        '龍洞浮標' => '46694A',
        '龜山島浮標' => '46708A',
        '成功浮標' => '46761F',
        '鵝鑾鼻浮標' => '46759A',
        '小琉球浮標' => '46714D',
        '彌陀浮標' => 'COMC08'
    ];
    $CLI->clear();
    $input = $CLI->radio('選擇要分析的浮標資料: ', array_keys($allBuoys));
    $buoyName = $input->prompt();
    $CLI->yellow('正在取得浮標資料中, 請稍候...');
    $CLI->border('~', 77);
    return $allBuoys[$buoyName];
}

$CLI = new CLImate;

// Populate buoy data by iterating through all selected stations
// for ($i = 0; $i < count($buoys); $i++) {
while ($buoyID = getUserInput($CLI)) {
    try {
        $buoy = new Buoy($buoyID);
        $CLI->lightGreen($buoy->getBuoyName());
        // 1.2.0: Use getBuoyReportArray(LATEST_X_HOURS) to get report in 
        // array so the report can be displayed by $CLI->table formation
        // $CLI->out($buoy->getBuoyReport(LATEST_X_HOURS));
        $CLI->table($buoy->getBuoyReportArray(LATEST_X_HOURS, VERBOSE_LEVEL));
        $buoyLog->info('Successfully reported buoy info for ' . $buoy->getBuoyName(), array('buoyID' => $buoyID));
        $confirm = $CLI->yellow()->confirm('選擇其他浮標?');
        if (!$confirm->confirmed()) {
            break;
        }
    } catch (\Exception $e) {
        $CLI->red('錯誤: 無法取得浮標編號 ' . $buoyID . ' 資料!');
        $buoyLog->error('Failed to connect to buoy data source. Error message: ' . $e->getMessage(), array('Script' => $e->getFile(), 'Line No.' => $e->getLine()));
    }
}
