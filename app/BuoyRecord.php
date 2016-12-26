<?php

namespace App;

use App\Buoy;

class BuoyRecord
{
    /**
     * The Buoy object this record associates with
     *
     * @var Buoy
     */
    private $buoy;

    /**
     * Date/Time of this record
     *
     * @var DateTime
     */
    public $recDateTime;

    /**
     * 浪高 (公尺)
     *
     * @var float
     */
    public $recWaveHeight;

    /**
     * 浪向
     *
     * @var string
     */
    public $recWaveDirection;

    /**
     * 波浪週期 (秒)
     *
     * @var float
     */
    public $recWavePeriod;

    /**
     * 風力 (公尺/秒)
     *
     * @var float
     */
    public $recWindSpeed;

    /**
     * 風力 (級)
     *
     * @var string
     */
    public $recWindSpeedCategory;

    /**
     * 風向
     *
     * @var string
     */
    public $recWindDirection;

    /**
     * 陣風 (公尺/秒)
     *
     * @var float
     */
    public $recGustSpeed;

    /**
     * 陣風 (級)
     *
     * @var string
     */
    public $recGustSpeedCategory;

    /**
     * 海溫 (攝氏, 度)
     *
     * @var float
     */
    public $recSeaTemperature;

    /**
     * 氣溫 (攝氏, 度)
     *
     * @var float
     */
    public $recAirTemperature;

    /**
     * 氣壓 (百帕)
     *
     * @var float
     */
    public $recAirPressure;

    /**
     * BuoyRecord object constructor that instantiates the Buoyrecord object
     *
     * @return BuoyRecord
     * @author Chien-pin Wang <Wang.ChienPin@gmail.com>
     */
    public function __construct(Buoy &$buoy, $recDate, $recTime, $recWaveHeight, $recWaveDirection, $recWavePeriod, $recWindSpeed, $recWindSpeedCategory, $recWindDirection, $recGustSpeed, $recGustSpeedCategory, $recSeaTemperature, $recAirTemperature, $recAirPressure)
    {
        $this->buoy = $buoy;
        $dateString = date('Y') . '/' . preg_split('/ /', $recDate)[0] . ' ' . $recTime;
        $this->recDateTime = \DateTime::createFromFormat('Y/m/d H:i', $dateString);
        $this->recWaveHeight = $recWaveHeight;
        $this->recWaveDirection = $recWaveDirection;
        $this->recWavePeriod = $recWavePeriod;
        $this->recWindSpeed = $recWindSpeed;
        $this->recWindSpeedCategory = $recWindSpeedCategory;
        $this->recWindDirection = $recWindDirection;
        $this->recGustSpeed = $recGustSpeed;
        $this->recGustSpeedCategory = $recGustSpeedCategory;
        $this->recSeaTemperature = $recSeaTemperature;
        $this->recAirTemperature = $recAirTemperature;
        $this->recAirPressure = $recAirPressure;

        return $this;
    }

    /**
     * Display selected buoy record
     *
     * Now include 日期, 時間, 浪高, 浪向, 週期, 風力, 海溫, 氣溫
     *
     * @return string
     * @author Chien-pin Wang <Wang.ChienPin@gmail.com>
     */
    public function getBuoyRecord()
    {
        $message = '';
        $message .= $this->recDateTime->format('[m/d H:i]') . ' ';
        $message .= '浪高' . $this->recWaveHeight . '米; ';
        $message .= '浪向' . $this->recWaveDirection . '; ';
        $message .= '週期' . $this->recWavePeriod . '秒; ';
        $message .= '風力' . $this->recWindSpeedCategory . '級; ';
        $message .= '海溫' . $this->recSeaTemperature . '度; ';
        $message .= '氣溫' . $this->recAirTemperature . '度.';

        return $message;
    }
}
