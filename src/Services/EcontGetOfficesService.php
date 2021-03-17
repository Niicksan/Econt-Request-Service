<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

class EcontGetOfficesService {
    private $logger;
    private $econtRequest;

    public function __construct(LoggerInterface $logger, EcontRequestService $econtRequest)
    {
        $this->logger = $logger;
        $this->econtRequest = $econtRequest;
    }

    public function getOffices(string $cityID)
    {
        return $this->econtRequest->econtRequest("Nomenclatures/NomenclaturesService.getOffices.json", array('countryCode' => 'BGR', 'cityID' => $cityID));
    }
}