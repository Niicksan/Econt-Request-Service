<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

class EcontGetCitiesService {

    public $resultCities;
    private $logger;

    public function __construct(LoggerInterface $logger, EcontRequestService $econtRequestService)
    {
        $this->logger = $logger;
        $this->resultCities = $this->getCities($econtRequestService);
    }

    private function getCities($econtRequestService)
    {
        $resultCities = $econtRequestService->econtRequest("Nomenclatures/NomenclaturesService.getCities.json", array('countryCode' => 'BGR'));

        return $resultCities;
    }
}