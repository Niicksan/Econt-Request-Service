<?php


namespace App\Services;


use Psr\Log\LoggerInterface;

class EcontCalculatePriceService
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function calculatePrice(string $cityFrom, string $cityFromPostCode, string $cityTo, string $cityToPostCode, EcontRequestService $requestService)
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
                    'street' => 'Димитър Димитров',
                    'num' => '7'
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
                    'street' => 'Муткурова',
                    'num' => '84',
                    'other' => 'бл. 5, вх. А, ет. 6'
                ),
                'packCount' => 1,
                'shipmentType' => 'PACK',
                'weight' => 5,
                'shipmentDescription' => 'пратка с описание',
            ),
            'mode' => 'calculate'
        );

        return $requestService->econtRequest("Shipments/LabelService.createLabel.json", $arrObj);
    }
}