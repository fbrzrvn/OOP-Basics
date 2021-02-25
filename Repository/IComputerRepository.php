<?php

interface IComputerRepository {
  public function add(Computer $computer);
  public function getAllComputers();
  public function getDesktopById(String $id);
  public function getLaptopById(String $id);
}

