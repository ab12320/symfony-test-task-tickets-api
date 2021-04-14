<?php

namespace App\Service;

use App\Entity\Flight;
use App\Message\FlightIsCancelled;
use App\Repository\FlightRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use stdClass;

class FlightManager
{
    public const FLIGHT_SALE_IS_COMPLETE = 'flight_ticket_sales_completed';
    public const FLIGHT_IS_CANCELLED = 'flight_cancelled';
    private Flight $flight;
    private EntityManagerInterface $entityManager;
    private FlightRepository $flightRepository;
    private MessageBusInterface $messageBus;

    public function __construct(EntityManagerInterface $entityManager, FlightRepository $flightRepository, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->flightRepository = $flightRepository;
        $this->messageBus = $messageBus;
    }

    public function handleEvent(stdClass $data)
    {
        $this->flight = $this->flightRepository->find($data->flight_id);

        if ($data->event === self::FLIGHT_SALE_IS_COMPLETE) {
            $this->completeTicketsSale();
        }

        if ($data->event === self::FLIGHT_IS_CANCELLED) {
            $this->cancelFlight();
        }
    }

    private function completeTicketsSale()
    {
        $this->flight->setStatus(Flight::SALE_IS_COMPLETE);
        $this->entityManager->persist($this->flight);
        $this->entityManager->flush();
    }

    private function cancelFlight()
    {
        $this->flight->setStatus(Flight::IS_CANCELLED);
        $this->entityManager->persist($this->flight);
        $this->entityManager->flush();

        $message = new FlightIsCancelled($this->flight->getId());
        $this->messageBus->dispatch($message);
    }
}
