<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

class EcontGetCitiesService {

    private $logger;
    private $econtRequest;
    public $resultCities;

    public function __construct(LoggerInterface $logger, EcontRequestService $econtRequest)
    {
        $this->logger = $logger;
        $this->econtRequest = $econtRequest;
        $this->resultCities = $this->getCities();
    }

    private function getCities()
    {
        return $this->econtRequest->econtRequest("Nomenclatures/NomenclaturesService.getCities.json", array('countryCode' => 'BGR'));
    }
}