<?php

namespace App\Controller;

use App\Request\TicketRequest;
use App\Service\TicketManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class TicketController extends AbstractController
{
    private TicketManager $ticketManager;

    public function __construct(TicketManager $ticketManager)
    {
        $this->ticketManager = $ticketManager;
    }

    /**
     * @Route("/reserve-place", name="reserve-place", methods={"POST"})
     */
    public function reservePlace(TicketRequest $request): Response
    {
        if ($this->ticketManager->reservePlace($request->getTicketId(), $request->getCustomerId())) {
            return $this->json([
                'message' => 'Reserved!'
            ]);
        }

        throw new BadRequestHttpException();
    }

    /**
     * @Route("/reject-reserved-place", name="reject-reserved-place", methods={"POST"})
     */
    public function rejectReservedPlace(TicketRequest $request): Response
    {
        if ($this->ticketManager->rejectReservedPlace($request->getTicketId(), $request->getCustomerId())) {
            return $this->json([
                'message' => 'Rejected!'
            ]);
        }

        throw new BadRequestHttpException();
    }

    /**
     * @Route("/buy-ticket", name="buy-ticket", methods={"POST"})
     */
    public function buyTicket(TicketRequest $request): Response
    {
        if ($this->ticketManager->buyTicket($request->getTicketId(), $request->getCustomerId())) {
            return $this->json([
                'message' => 'Bought!'
            ]);
        }

        throw new BadRequestHttpException();
    }

    /**
     * @Route("/refund-ticket", name="refund-ticket", methods={"POST"})
     */
    public function refundTicket(TicketRequest $request): Response
    {
        if ($this->ticketManager->refundTicket($request->getTicketId(), $request->getCustomerId())) {
            return $this->json([
                'message' => 'Refunded!'
            ]);
        }

        throw new BadRequestHttpException();
    }
}
