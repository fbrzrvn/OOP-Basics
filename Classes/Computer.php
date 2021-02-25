<?php

abstract class Computer {
  public Int $computerId;
  public String $brand;
  public OS $os;
  public String $serialNumber;

  public function __construct ($computerId, $brand, $os, $serialNumber) {
    $this->computerId = $computerId;
    $this->brand = $brand;
    $this->os = $os;
    $this->serialNumber = $serialNumber;
  }
}