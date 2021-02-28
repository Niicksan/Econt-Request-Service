<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

class EcontGetOfficesService {
    private  $logger;
    private  $econtRequestService;
    public array $resultOffices;

    public function __construct(LoggerInterface $logger, EcontRequestService $econtRequestService)
    {
        $this->logger = $logger;
        $this->econtRequestService = $econtRequestService;
        $this->resultOffices = $this->getOffices();
    }

    private function getOffices()
    {
        return $this->econtRequestService->econtRequest("Nomenclatures/NomenclaturesService.getOffices.json", array('countryCode' => 'BGR'));
    }
}