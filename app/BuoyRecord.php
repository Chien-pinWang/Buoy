<?php

namespace App;

use App\Buoy;

class BuoyRecord
{
    private $buoy;
    public $recDateTime;
    public $recWaveHeight;
    public $recWaveDirection;
    public $recWavePeriod;
    public $recWindSpeed;
    public $recWindSpeedCategory;
    public $recWindDirection;
    public $recGustSpeed;
    public $recGustSpeedCategory;
    public $recSeaTemperature;
    public $recAirTemperature;
    public $recAirPressure;

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
    }

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
