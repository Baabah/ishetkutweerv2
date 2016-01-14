<?php

namespace Location;

class Station
{
    private $knmiId;
    private $buienradarId;
    private $name;
    private $lat;
    private $lon;

    public function __construct($knmiId, $buienradarId, $name, $lat, $lon)
    {
        $this->knmiId = $knmiId;
        $this->buienradarId = $buienradarId;
        $this->name = $name;
        $this->lat = $lat;
        $this->lon = $lon;
    }

    public function toArray()
    {
        return
        [
            'latitude' => $this->lat,
            'longitude' => $this->lon,
            'station' => $this
        ];
    }

    public function getBuienradarId()
    {
        return $this->buienradarId;
    }

    public function getKnmiId()
    {
        return $this->knmiId;
    }

}
 