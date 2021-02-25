<?php

class OS {
    public String $family;
    public String $distro;
    public String $version;

    public function __construct ($family,  $distro, $version) {
      $this->family = $family;
      $this->distro = $distro;
      $this->version = $version;
    }

    public static function Windows($version) {
      return new OS("Windows", null, $version);
    }

    public static function Linux($distro, $version) {
      return new OS("Linux", $distro, $version);
    }
  }