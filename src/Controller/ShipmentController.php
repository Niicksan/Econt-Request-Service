<?php

namespace App\Controller;

use App\Entity\Shipment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShipmentController extends AbstractController
{
    /**
     * @Route("/econt", name="econt")
     * @param $request
     * @return Response
     */
    public function getShipments(): Response
    {
        $shipments = $this
            ->getDoctrine()
            ->getRepository(Shipment::class)
            ->findAll();

        return $this->render('econt/econt.html.twig',
            ['shipments' => $shipments]
        );
    }
}
