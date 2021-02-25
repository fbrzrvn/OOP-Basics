<?php

include realpath(__DIR__.'/../Classes/OS/OS.php');
include realpath(__DIR__.'/../Classes/Computer.php');
include realpath(__DIR__.'/../Classes/DesktopPC.php');
include realpath(__DIR__.'/../Classes/Laptop.php');
include realpath(__DIR__.'/../Repository/IComputerRepository.php');
include realpath(__DIR__.'/../Repository/ComputerRepository.php');


class ComputerManager {

    private array $computers = [];

    private IComputerRepository $computerRepository;

    public function __construct(IComputerRepository $computerRepository) {
        $this->computerRepository = $computerRepository;
        $this->computers = $computerRepository->getAllComputers();
    }

    public function getComputers() {
        return $this->computers;
    }

    public function addComputer($computer) {
        $this->computerRepository->add($computer);
        array_push($this->computers, $computer);
    }

    public function getDesktop($id) {
        return array_search($id, array_column($this->computers, 'desktopId'));
    }

    public function getLaptop($id) {
        return array_search($id, array_column($this->computers, 'laptopId'));
    }
}

$repository = new ComputerRepository();
$manager = new ComputerManager($repository);

echo var_dump($manager->addComputer(DesktopPC::Windows("spanish", "brand_chula", 123123)));
echo var_dump($manager->getComputers());