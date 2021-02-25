<?php

define('HOST', "127.0.0.1");
define('USERNAME', "faber");
define('PASSWORD', "userpass");
define('DATABASE', "computer");

class ComputerRepository implements IComputerRepository{

  private String $conn;

  public function __construct()
  {
    $this->conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
    if (!$this->conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
  }

  public function add(Computer $computer)
  {
    $osId = $this->addOs($computer->os);
    $computerId = $this->addComputerRow($computer, $osId);
    if($computer instanceof DesktopPC) {
      $this->addDesktop($computer, $computerId);
    } else if($computer instanceof Laptop) {
      $this->addLaptop($computer, $computerId);
    }
  }

  public function getAllComputers()
  {
    return array_merge($this->getLaptops(), $this->getDesktopPCs());
  }

  public function getDesktopById(string $id)
  {
    $query = "SELECT
      c.id as computer_id, c.brand, c.serial_number,
      os.family, os.version, os.distro,
      d.keyboard, d.id
    FROM computer c
    INNER JOIN os
    ON os.id = c.os_id
    INNER JOIN desktop d
    ON d.computer_id = c.id
    WHERE d.id = $id";
  
    $result = mysqli_query($this->conn, $query)->fetch_assoc();
  
    $os = new OS($result['family'], $result['distro'], $result['version']);
    return new DesktopPC($result['id'], $result['computer_id'], $result['keyboard'], $result['brand'], $os, $result['serial_number']);
  }

  public function getLaptopById(string $id)
  {
    $sql = "SELECT
      c.id as computer_id, c.brand, c.serial_number,
      os.family, os.version, os.distro,
      l.battery_duration, l.weigth, l.id
    FROM computer c
    INNER JOIN os
    ON os.id = c.os_id
    INNER JOIN laptop l
    ON l.computer_id = c.id
    WHERE l.id = $id";
    $result = mysqli_query($this->conn, $sql)->fetch_assoc();
  
    $os = new OS($result['family'], $result['distro'], $result['version']);
    return new Laptop($result['id'], $result['computer_id'], $result['brand'], $os, $result['serial_number'], $result['battery_duration'], $result['weigth']);
  }

  private function getLaptops() {
    $query = "SELECT
      c.id as computer_id, c.brand, c.serial_number,
      os.family, os.version, os.distro,
      l.battery_duration, l.weigth, l.id
    FROM computer c
    INNER JOIN os
    ON os.id = c.os_id
    INNER JOIN laptop l
    ON l.computer_id = c.id";
  
    $results = mysqli_query($this->conn, $query)->fetch_all(MYSQLI_ASSOC);
  
    $laptops = array_map(function($result) {
      $os = new OS($result['family'], $result['distro'], $result['version']);
      return new Laptop($result['id'], $result['computer_id'], $result['brand'], $os, $result['serial_number'], $result['battery_duration'], $result['weigth']);
    }, $results);
  
    return $laptops;
  }
  
  private function getDesktopPCs() {
    $query = "SELECT
      c.id as computer_id, c.brand, c.serial_number,
      os.family, os.version, os.distro,
      d.keyboard, d.id
    FROM computer c
    INNER JOIN os
    ON os.id = c.os_id
    INNER JOIN desktop d
    ON d.computer_id = c.id";
  
    $results = mysqli_query($this->conn, $query)->fetch_all(MYSQLI_ASSOC);
  
    $desktopPCs = array_map(function($result) {
      $os = new OS($result['family'], $result['distro'], $result['version']);
      return new DesktopPC($result['id'], $result['computer_id'], $result['keyboard'], $result['brand'], $os, $result['serial_number']);
    }, $results);
  
    return $desktopPCs;
  }

  private function addComputerRow(Computer $computer, $osId) {
    $query = "INSERT INTO computer (brand, os_id, serial_number) 
      VALUES ('$computer->brand', $osId, '$computer->serialNumber')";
    mysqli_query($this->conn, $query);
    return mysqli_insert_id($this->conn);
  }
  
  private function addDesktop(DesktopPC $desktop, $computerId) {
    $query = "INSERT INTO desktop (keyboard, computer_id) 
      VALUES ('$desktop->keyboardEncoding', $computerId)";
    mysqli_query($this->conn, $query);
    return mysqli_insert_id($this->conn);
  }
  
  private function addLaptop(Laptop $laptop, $computerId) {
    $query = "INSERT INTO laptop (battery_duration, weigth, computer_id)
      VALUES ($laptop->batteryDuration, $laptop->weigth, $computerId);";
    mysqli_query($this->conn, $query);
    return mysqli_insert_id($this->conn);
  }
  
  private function addOs($os) {
    $distro = $os->distro ? "'$os->distro'" : "null";
    $query = "INSERT INTO os (family, distro, version) 
      VALUES ('$os->family', $distro, '$os->version')";
    mysqli_query($this->conn, $query);
    return mysqli_insert_id($this->conn);
  }
}