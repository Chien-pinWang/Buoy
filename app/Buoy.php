<?php

namespace App;

use \simple_html_dom;
use App\BuoyRecord;
use RunningStat\RunningStat;

class Buoy
{
    /**
     * Buoy ID as specified at CWB web site
     *
     * @var string $buoyID 
     */
    private $buoyID;

    /**
     * Name of the buoy station
     *
     * @var string $buoyName
     */
    private $buoyName;

    /**
     * Buoy data fetched from CWB web page, BuoyRecord objects
     *
     * @var array $buoyRecords
     */
    private $buoyRecords = array();

    const BUOY_URL_PREFIX = 'http://www.cwb.gov.tw/V7/marine/sea_condition/cht/tables/';

    // Fetch the most recent MAX_RECORDS (MAX_RECORDS <= 72)
    const MAX_RECORDS = 24;

    /**
     * Buoy object constructor that instantiates a Buoy object by BuoyID
     *
     * Constructor tries to fetch data from CWB web site and load
     * buoyRecords if connected successfully. Throw an exception if 
     * network is not availabe or the URL has been changed.
     *
     * @return Buoy
     * @author Chien-pin Wang <Wang.ChienPin@gmail.com>
     */
    public function __construct($buoyID) 
    {
        $this->buoyID = $buoyID;
        $buoyURL = Buoy::BUOY_URL_PREFIX . $this->buoyID . '.html';

        // Suppress PHP connection failure warning by setting error
        // handler with a null function and test connectivity to the
        // URL. This is to prevent simple_html_dom being fooled by
        // checking the latest PHP error.
        set_error_handler(function (){
        
        }, E_ALL);
        $connectivity = @file_get_contents($buoyURL);
        restore_error_handler();
        if (!$connectivity) {
            throw new \Exception('Failed to connect to buoy data source at ' . $buoyURL);
        } else {
            $html = new simple_html_dom($buoyURL);
            $this->buoyName = mb_substr($html->find('title', 0)->plaintext, 0, mb_strpos($html->find('title', 0)->plaintext, '資料'));
            $this->loadBuoyRecords($html);
        }
    }

    /**
     * Getter function for buoy name
     *
     * @return string
     * @author Chien-pin Wang <Wang.ChienPin@gmail.com>
     */
    public function getBuoyName()
    {
        if ($this->buoyName) {
            return $this->buoyName . '浮標';
        } else {
            return '浮標編號' . $this->buoyID;
        }
    }

    /**
     * Load buoyRecords with data from CWB web site.
     *
     * Buoy data is fetched and parsed to BuoyRecord object and
     * added to the buoyRecords array.
     *
     * @return void
     * @author Chien-pin Wang <Wang.ChienPin@gmail.com>
     */
    public function loadBuoyRecords(simple_html_dom $html)
    {
        for ($i = 2; $i < Buoy::MAX_RECORDS + 2; $i++) {
            $tr = $html->find('tr', $i);
            $recDate = $tr->find('td', 0)->plaintext;
            $recTime = $tr->find('td', 1)->plaintext;
            $recWaveHeight = str_replace('&nbsp;', '-', $tr->find('td', 3)->plaintext);
            $recWaveDirection = str_replace('&nbsp;', '-', $tr->find('td', 4)->plaintext);
            $recWavePeriod = str_replace('&nbsp;', '-', $tr->find('td', 5)->plaintext);
            $recWindSpeed = str_replace('&nbsp;', '-', $tr->find('td', 6)->plaintext);
            $recWindSpeedCategory = str_replace('&nbsp;', '-', $tr->find('td', 7)->plaintext);
            $recWindDirection = str_replace('&nbsp;', '-', $tr->find('td', 8)->plaintext);
            $recGustSpeed = str_replace('&nbsp;', '-', $tr->find('td', 9)->plaintext);
            $recGustSpeedCategory = str_replace('&nbsp;', '-', $tr->find('td', 10)->plaintext);
            $recSeaTemperature = str_replace('&nbsp;', '-', $tr->find('td', 11)->plaintext);
            $recAirTemperature = str_replace('&nbsp;', '-', $tr->find('td', 12)->plaintext);
            $recAirPressure = str_replace('&nbsp;', '-', $tr->find('td', 13)->plaintext);

            $this->buoyRecords[] = new BuoyRecord($this, $recDate, $recTime, $recWaveHeight, $recWaveDirection, $recWavePeriod, $recWindSpeed, $recWindSpeedCategory, $recWindDirection, $recGustSpeed, $recGustSpeedCategory, $recSeaTemperature, $recAirTemperature, $recAirPressure);

        }

        // clear the simple_dom_html object to free up memory
        $html->clear();
    }

    /**
     * Report the latest X hour buoy data and trend
     *
     * DEPRECATED (1.2.0): 
     * Use getBuoyReportArray($count) instead to avoid formatting results
     *
     * Report the latest 3 hour buoy data
     * Analyze trends of 浪高, 海溫, 氣溫
     *
     * @return string
     * @author Chien-pin Wang <Wang.ChienPin@gmail.com>
     */
    public function getBuoyReport($count)
    {
        // Report the latest $count hours of buoy data
        $report = '';
        for ($i = 0; $i < 3; $i++) {
            $report .= $this->buoyRecords[$i]->getBuoyRecord() . PHP_EOL;
        }
        $report .= '最近 ' . $count . ' 小時';

        // Get stats of the wave height
        $stats = $this->getStats($count, 'recWaveHeight');
        $report .= '浪高平均 ' . $stats['avg'] . '米; 最大 ' .$stats['max'] . '米; 最小 ' . $stats['min'] . '米; ';
        if ($stats['trend'] == '+') {
            $report .= '目前正在起浪中';
        } else {
            $report .= '目前浪在消退中';
        }
        unset($stats);

        // Get stats of the sea temperature
        $report .= PHP_EOL;
        $stats = $this->getStats($count, 'recSeaTemperature');
        $report .= '           海溫平均 ' . $stats['avg'] . '度; 最高 ' .$stats['max'] . '度; 最低 ' . $stats['min'] . '度; ';
        if ($stats['trend'] == '+') {
            $report .= '目前正在增溫中';
        } else {
            $report .= '目前正在降溫中';
        }
        unset($status);

        // Get stats of the air temperature
        $report .= PHP_EOL;
        $stats = $this->getStats($count, 'recAirTemperature');
        $report .= '           氣溫平均 ' . $stats['avg'] . '度; 最高 ' .$stats['max'] . '度; 最低 ' . $stats['min'] . '度; ';
        if ($stats['trend'] == '+') {
            $report .= '目前正在增溫中';
        } else {
            $report .= '目前正在降溫中';
        }
        unset($status);

        return $report;
    }

    /**
     * 
     * Report the latest X hour buoy data and trend
     *
     * Report the latest 3 hour buoy data
     * Analyze trends of 浪高, 海溫, 氣溫
     *
     * @return array
     * @author Chien-pin Wang <Wang.ChienPin@gmail.com>
     */
    public function getBuoyReportArray($count, $verbose = 1)
    {
        // $verbose==0 reports buoy data and trend;
        // $verbose==1 reports buoy data, average, and trend;
        // $verbose==2 reports buoy data, average, max, min, and trend
        // $verbose==3 reports everthing in 2, stddev, variation, and range
        // Report the latest $count hours of buoy data
        $report = array();
        for ($i = 0; $i < 3; $i++) {
            $report[] = $this->buoyRecords[$i]->getBuoyRecordArray();
        }

        // Get stats of the wave height
        $statsWaveHeight = $this->getStats($count, 'recWaveHeight');
        $statsSeaTemperature= $this->getStats($count, 'recSeaTemperature');
        $statsAirTemperature= $this->getStats($count, 'recAirTemperature');
        $report[] = [
            '時間' => '近8小時趨勢',
            '浪高/米' => '',
            '浪向' => '',
            '週期/秒' => '',
            '風力/級' => '',
            '海溫/度C' => '',
            '氣溫/度C' => ''
        ];

        // $verbose determines the detail level of the report
        if ($verbose > 0) {
            $report[] = [
                '時間' => '平均',
                '浪高/米' => $statsWaveHeight['avg'],
                '浪向' => '',
                '週期/秒' => '',
                '風力/級' => '',
                '海溫/度C' => $statsSeaTemperature['avg'],
                '氣溫/度C' => $statsAirTemperature['avg']
            ];
        }
        if ($verbose > 1) {
            $report[] = [
                '時間' => '最大',
                '浪高/米' => $statsWaveHeight['max'],
                '浪向' => '',
                '週期/秒' => '',
                '風力/級' => '',
                '海溫/度C' => $statsSeaTemperature['max'],
                '氣溫/度C' => $statsAirTemperature['max']
            ];
            $report[] = [
                '時間' => '最小',
                '浪高/米' => $statsWaveHeight['min'],
                '浪向' => '',
                '週期/秒' => '',
                '風力/級' => '',
                '海溫/度C' => $statsSeaTemperature['min'],
                '氣溫/度C' => $statsAirTemperature['min']
            ];
        }
        if ($verbose > 2) {
            $report[] = [
                '時間' => '標準差',
                '浪高/米' => $statsWaveHeight['stddev'],
                '浪向' => '',
                '週期/秒' => '',
                '風力/級' => '',
                '海溫/度C' => $statsSeaTemperature['stddev'],
                '氣溫/度C' => $statsAirTemperature['stddev']
            ];
            $report[] = [
                '時間' => '變異數',
                '浪高/米' => $statsWaveHeight['variance'],
                '浪向' => '',
                '週期/秒' => '',
                '風力/級' => '',
                '海溫/度C' => $statsSeaTemperature['variance'],
                '氣溫/度C' => $statsAirTemperature['variance']
            ];
            $report[] = [
                '時間' => '範圍',
                '浪高/米' => $statsWaveHeight['range'],
                '浪向' => '',
                '週期/秒' => '',
                '風力/級' => '',
                '海溫/度C' => $statsSeaTemperature['range'],
                '氣溫/度C' => $statsAirTemperature['range']
            ];
        }

        // Trend is determined by sign-based direction
        // emphasized by 'attribute'
        //
        // Determine wave height trend
        $thresholdWH = [
            'attribute' => 'stddev',
            'threshold' => [
                [ 'level' => 0.3, 'increase' => '無變化', 'decrease' => '無變化' ],
                [ 'level' => 0.5, 'increase' => '起浪', 'decrease' => '消退' ],
                [ 'level' => 'max', 'increase' => '快速起浪', 'decrease' => '快速消退']
            ]];
        $statsWaveHeight['trend'] = $this->getTrend($statsWaveHeight, $thresholdWH);

        // Determine sea temperature trend
        $thresholdST = [
            'attribute' => 'stddev',
            'threshold' => [
                [ 'level' => 0.3, 'increase' => '無變化', 'decrease' => '無變化' ],
                [ 'level' => 0.5, 'increase' => '增溫', 'decrease' => '降溫' ],
                [ 'level' => 'max', 'increase' => '快速增溫', 'decrease' => '快速降溫' ]
            ]];
        $statsSeaTemperature['trend'] = $this->getTrend($statsSeaTemperature, $thresholdST);

        // Determine air temperature trend
        $thresholdAT = [
            'attribute' => 'stddev',
            'threshold' => [
                [ 'level' => 0.3, 'increase' => '無變化', 'decrease' => '無變化' ],
                [ 'level' => 0.5, 'increase' => '增溫', 'decrease' => '降溫' ],
                [ 'level' => 'max', 'increase' => '快速增溫', 'decrease' => '快速降溫' ]
            ]];
        $statsAirTemperature['trend'] = $this->getTrend($statsAirTemperature, $thresholdAT);

        $report[] = [
            '時間' => '趨勢',
            '浪高/米' => $statsWaveHeight['trend'],
            '浪向' => '',
            '週期/秒' => '',
            '風力/級' => '',
            '海溫/度C' => $statsSeaTemperature['trend'],
            '氣溫/度C' => $statsAirTemperature['trend']
        ];

        return $report;
    }

    /**
     * Determine the trend of change modified by stats attribute
     *
     * @return string
     * @author Chien-pin Wang <Wang.ChienPin@gmail.com>
     */
    private function getTrend($stats, $threshold)
    {
        $direction = $stats['trend'];
        if ($direction == '+') {
            for ($i = 0; $i < count($threshold['threshold']); $i++) {
                if ($stats[$threshold['attribute']] < $threshold['threshold'][$i]['level']) {
                    return $threshold['threshold'][$i]['increase'];
                }
            }
            return $threshold['threshold'][$i-1]['increase'];
        } else if ($direction == '-') {
            for ($i = 0; $i < count($threshold['threshold']); $i++) {
                if ($stats[$threshold['attribute']] < $threshold['threshold'][$i]['level']) {
                    return $threshold['threshold'][$i]['decrease'];
                }
            }
            return $threshold['threshold'][$i-1]['decrease'];
        } else { // $direction == ''
            // return $threshold['threshold'][0]['increase'];
            return '無資料';
        }
    }

    /**
     * Calculate statistics of the buoy data
     *
     * Calculate the min, max, average, and change direction of a given
     * attribute. An associate array of the stats is returned.
     *
     * @return array
     * @author Chien-pin Wang <Wang.ChienPin@gmail.com>
     */
    private function getStats($count, $attribute) 
    {
        $increase = 0;
        $decrease = 0;

        $rstat = new RunningStat();
        for ($i = 0; $i < $count; $i++) {
            $value = $this->buoyRecords[$i]->$attribute;
            if (is_numeric($value)) {
                $rstat->addObservation($value);

                // Determine trend, establish counts
                // Simple algorithm by comparing increase and decrease counts
                if (!isset($lastValue)) {
                    $lastValue = $value;
                } else {
                    if ($value >= $lastValue) {
                        $decrease++;
                    } else {
                        $increase++;
                    }
                }
                $lastValue = $value;

            }
        }

        // Say its increasing if increase counts >= decrease counts
        if ($increase == 0) {
            $trend = '';
        }
        else if ($increase >= $decrease) {
            $trend = '+';
        } else {
            $trend = '-';
        }

        if ($rstat->getCount() > 0) {
            $stats = [
                'min' => $rstat->min, 
                'max' => $rstat->max, 
                'avg' => round($rstat->getMean(), 2), 
                'stddev' => round($rstat->getstddev(), 2), 
                'variance' => round($rstat->getvariance(), 2),
                'range' => round($rstat->max - $rstat->min, 2),
                'trend' => $trend
            ];
        } else {
            $stats = [
                'min' => '-', 
                'max' => '-', 
                'avg' => '-', 
                'stddev' => '-', 
                'variance' => '-',
                'range' => '-',
                'trend' => ''
            ];

        }

        return $stats;
    }

}
