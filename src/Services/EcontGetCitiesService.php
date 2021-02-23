<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

class EcontGetCitiesService {

    private $logger;
    private $econtRequestService;
    public $resultCities;

    public function __construct(LoggerInterface $logger, EcontRequestService $econtRequestService)
    {
        $this->logger = $logger;
        $this->econtRequestService = $econtRequestService;
        $this->resultCities = $this->getCities();
    }

    private function getCities()
    {
        return $this->econtRequestService->econtRequest("Nomenclatures/NomenclaturesService.getCities.json", array('countryCode' => 'BGR'));
    }
}