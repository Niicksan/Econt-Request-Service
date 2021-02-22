<?php

namespace App\Controller;

use App\Entity\Shipment;
use App\Form\EcontCitiesType;
use App\Services\EcontCalculatePriceService;
use App\Services\EcontGetCitiesService;
use App\Services\EcontRequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EcontController extends AbstractController
{
    /**
     * @Route("/econt-new", name="econt_new")
     * @param Request $request
     * @param EcontGetCitiesService $citiesService
     * @return Response
     */
    public function index(Request $request, EcontGetCitiesService $econtGetCitiesService, EcontCalculatePriceService $calculatePriceService, EcontRequestService $requestService): Response
    {
        $citiesService = $econtGetCitiesService->resultCities; // Всички градове от еконт

        //$result = $this->econtRequest("Nomenclatures/NomenclaturesService.getOffices.json"); // Всички оофиси от еконт

        //$citiesInfo= $this->getCitiiesInfo($citiesService); // Информация, id на град, код на града и име на града
        $citiesName = $this->getCitiiesNames($citiesService); // Имена на градавете + пощенските кодове (просто тест)

        $shipment = new Shipment();
        $form = $this->createForm(EcontCitiesType::class, $citiesName);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Get Submited Data
            $data = $form->getData();

            $cityFromData = $data['from'];
            $cityToData = $data['to'];

            //Parse input Data
            list($postCode, $city) = preg_split('[ ]', $cityFromData);
            $cityFrom = $city;
            $cityFromPostCode = $postCode;

            list($postCode, $city) = preg_split('[ ]', $cityToData);
            $cityTo = $city;
            $cityToPostCode = $postCode;

            $resultPrice = $calculatePriceService->calculatePrice($cityFrom, $cityFromPostCode, $cityTo, $cityToPostCode, $requestService);

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

    private function getCitiiesNames($resultCities)
    {
        $citiesName = [];

        foreach ($resultCities['cities'] as $city) {
            if ($city['country']['code3'] == 'BGR')
            {
                $label = $city['postCode'] . ' ' . $city['name'];
                $citiesName[$label] = $label;
            }
        }

        return $citiesName;
    }
}