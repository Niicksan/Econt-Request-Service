<?php


namespace App\Services;


use Psr\Log\LoggerInterface;

class EcontCalculatePriceService
{
    private $logger;
    private $requestService;

    public function __construct(LoggerInterface $logger, EcontRequestService $requestService)
    {
        $this->logger = $logger;
        $this->requestService = $requestService;
    }

    public function calculatePrice(string $cityFrom, string $cityFromPostCode, string $cityTo, string $cityToPostCode, float $weight)
    {
        $arrObj = array (
            'label' => array(
                'senderClient' => array(
                    'name' => 'Алъш-вериш ЕООД',
                    'phones' => array('08888888888')
                ),
                'senderAddress' => array(
                    'city' => array(
                        'country' => array (
                            'code3' => 'BGR'
                        ),
                        'name' => $cityFrom,
                        'postCode' => $cityFromPostCode
                    ),
                    'street' => 'Цар Самуил',
                    'num' => '74'
                ),
                'receiverClient' => array(
                    'name' => 'Димитър Димитров',
                    'phones' => array('0876543210')
                ),
                'receiverAddress' => array(
                    'city' => array(
                        'country' => array (
                            'code3' => 'BGR'
                        ),
                        'name' => $cityTo,
                        'postCode' => $cityToPostCode
                    ),
                    'street' => 'Свети Свети Кирил и Методий',
                    'num' => '10',
                ),
                'packCount' => 1,
                'shipmentType' => 'PACK',
                'weight' => $weight,
                'shipmentDescription' => 'пратка с описание',
            ),
            'mode' => 'calculate'
        );

        return $this->requestService->econtRequest("Shipments/LabelService.createLabel.json", $arrObj);
    }
}