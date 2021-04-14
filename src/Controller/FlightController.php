<?php

namespace App\Controller;

use App\Service\FlightManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightController extends AbstractController
{
    private FlightManager $flightManager;

    public function __construct(FlightManager $flightManager)
    {
        $this->flightManager = $flightManager;
    }

    /**
     * @Route("/api/v1/callback/events", methods={"POST"})
     */
    public function index(Request $request): Response
    {
        $content = json_decode($request->getContent());
        $this->flightManager->handleEvent($content->data);
        return $this->json([]);
    }
}
