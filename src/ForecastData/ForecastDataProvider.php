<?php

namespace ForecastData;

use Helpers\BeaufortCalculator;
use Location\Station;
use VertigoLabs\Overcast\Overcast;

class ForecastDataProvider
{
    private $overcast;
    private $beaufortCalculator;

    public function __construct(Overcast $overcast, BeaufortCalculator $beaufortCalculator)
    {
        $this->overcast = $overcast;
        $this->beaufortCalculator = $beaufortCalculator;
    }

    public function getDataByStation(Station $station)
    {
        $data = $this->overcast->getForecast(
            $station->getLat(),
            $station->getLon(),
            null,
            ['units' => 'ca']
        );
        return $this->toForecastData($data);
    }

    private function toForecastData($data)
    {
        $forecast = new ForecastData();
        foreach($data->getDaily()->getData() as $dayData) {
            $avgTemp = round(($dayData->getTemperature()->getMin() + $dayData->getTemperature()->getMax()) / 2, 1);
            $rain = round($dayData->getPrecipitation()->getIntensity(), 1);
            $windSpeed = intval(round($dayData->getWindSpeed(), 0));
            $beaufort = $this->beaufortCalculator->getBeaufort($windSpeed);
            $forecast->add(new ForecastDay(
                $dayData->getTime(),
                $avgTemp,
                $rain,
                $windSpeed,
                $beaufort
            ));
        }
        return $forecast;
    }
}
 