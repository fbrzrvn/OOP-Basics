<?php

class DesktopPC extends Computer{

  public Int $desktopId;
  public String $keyboardEncoding;

  public function __construct($id, $computerId, $keyboardEnc, $brand, $os, $serialNumber) {
    $this->keyboardEncoding = $keyboardEnc;
    parent::__construct($computerId, $brand, $os, $serialNumber);
  }

  public static function Windows($keyboardEnc, $brand, $serialNumber) {
    return new DesktopPC(0, 0, $keyboardEnc, $brand, OS::Windows(10), $serialNumber);
  }

}