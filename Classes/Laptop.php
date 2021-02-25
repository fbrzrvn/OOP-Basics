<?php

class Laptop extends Computer {

    public Int $desktopId;
    public Int $batteryDuration;
    public Int $weight;

    public function __construct($id, $computerId, String $brand, OS $os, String $serialNumber, Int $batteryDuration, Float $weight) {
      $this->batteryDuration = $batteryDuration;
      $this->weight = $weight;

      parent::__construct($computerId, $brand, $os, $serialNumber);
    }

    public static function Linux(String $brand, String $distro, Int $version, String $serialNumber, Int $batteryDuration, Float $weight)
    {
      return new Laptop(0, 0, $brand, OS::Linux($distro, $version), $serialNumber, $batteryDuration, $weight);
    }
}