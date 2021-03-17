<?php

namespace App\Controller;

use App\Entity\Shipment;
use App\Form\EcontCitiesType;
use App\Services\EcontCalculatePriceService;
use App\Services\EcontGetCitiesService;
use App\Services\EcontGetOfficesService;
use App\Services\EcontRequestService;
use ContainerIOyvnyQ\getEcontGetOfficesServiceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EcontController extends AbstractController
{
    /**
     * @Route("/econt-new", name="econt_new")
     * @param Request $request
     * @param EcontGetCitiesService $econtGetCitiesService
     * @param EcontCalculatePriceService $calculatePriceService
     * @param EcontGetOfficesService $getOfficesService
     * @return Response
     */
    public function index(Request $request, EcontRequestService $requestService, EcontGetCitiesService $econtGetCitiesService, EcontCalculatePriceService $calculatePriceService, EcontGetOfficesService $getOfficesService): Response
    {
        ($cities = $econtGetCitiesService->resultCities); // Всички градове от еконт

        ($officeCode = $getOfficesService->getOffices(47)['offices'][0]['code']);

        ($citiesName = $this->getCitiiesNames($cities)); // Имена на градавете + пощенските кодове (просто тест)


        ($cityFrom = $cities['cities'][38]);
        ($cityTo = $cities['cities'][10]);

        $cityId = '723';

        ($officesFrom = $getOfficesService->getOffices($cityFrom['id']));
        ($officesTo = $getOfficesService->getOffices($cityTo['id']));

        ($officesFrom['offices'][27]);

        $shipment = new Shipment();
        $form = $this->createForm(EcontCitiesType::class, array (
            'citiesName' => $citiesName,
            'officesFrom' => $officesFrom,
            'officesTo' => $officesTo
            )
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Get Submited Data
            $data = $form->getData();

            $cityFromData = $data['cityFrom'];
            $officeFromData = $data['officeFrom'];
            $cityToData = $data['cityTo'];
            $officeToData = $data['officeTo'];
            $weight = floatval($data['weight']);

            //Parse input Data
            list($postCode, $city) = preg_split('[ ]', $cityFromData);
            $cityFrom = $city;
            $cityFromPostCode = $postCode;

            list($postCode, $city) = preg_split('[ ]', $cityToData);
            $cityTo = $city;
            $cityToPostCode = $postCode;

            $cityIdFrom = $this->getCityId($cities, $cityFrom);
            $cityIdTo = $this->getCityId($cities, $cityTo);

            $resultPrice = $this->calculatePrice($requestService, $cityFrom, $cityFromPostCode, $cityTo, $cityToPostCode, $weight);

//            if ($officeFromData == '' || $officeToData == '')
//            {
//                $officesFrom = $getOfficesService->getOffices($cityIdFrom);
//                $officesTo = $getOfficesService->getOffices($cityIdTo);
//
//                return $this->redirectToRoute('econt_new', [
//                    'cityFrom' => $cityFromData,
//                    'officeFrom' => $officesFrom,
//                    'cityTo' => $cityToData,
//                    'officeTo' => $officesTo,
//                    'weight' => $weight
//                ]);
//
//            } else {
//
//                return $this->redirectToRoute('econt');
//            }

            $shipment -> setCityFrom($cityFrom);
            $shipment -> setCityTo($cityTo);
            $shipment -> setPrice($resultPrice['label']['totalPrice']);
            $shipment -> setCurrency($resultPrice['label']['currency']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($shipment);
            $em->flush();

            return $this->redirect('econt');
        }

        return $this->render('econt/econt_new.html.twig',
             array('form' => $form->createView())
        );
    }

//    private function getCitiiesInfo($resultCities)
//    {
//        $citiesInfo = [];
//
//        foreach ($resultCities['cities'] as $city) {
//            if ($city['country']['code3'] == 'BGR')
//            {
//                $citiesInfo[$city['name']]['cityId'] = $city['id'];
//                $citiesInfo[$city['name']]['cityPostCode'] = $city['postCode'];
//                $citiesInfo[$city['name']]['cityName'] = $city['name'];
//            }
//        }
//
//        return $citiesInfo;
//    }

    private function getCitiiesNames($cities)
    {
        foreach ($cities['cities'] as $city) {

            $label = $city['postCode'] . ' ' . $city['name'];
            $citiesName[$label] = $label;
        }

        return $citiesName;
    }

    private function getCityId($cities, $cityFrom)
    {
        $cityId = '';
        ($cityId);

        foreach ($cities as $city) {
            if ($city == $cityFrom) {
                $cityId = $city['id'];
            }
        }

        return $cityId;
    }

    public function calculatePrice(EcontRequestService $requestService, string $cityFrom, string $cityFromPostCode, string $cityTo, string $cityToPostCode, float $weight)
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

                ),
                'senderOfficeCode' => '5304',
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
                    'street' => 'ул. Пионерска',
                    'num' => '5',
                ),
                'receiverOfficeCode' => '1064',
                'packCount' => 1,
                'shipmentType' => 'PACK',
                'weight' => $weight,
                'shipmentDescription' => 'пратка с описание',
            ),
            'mode' => 'calculate'
        );

        return $requestService->econtRequest("Shipments/LabelService.createLabel.json", $arrObj);
    }
}