<?php

namespace App\Service;

use App\Entity\Flight;
use App\Entity\Ticket;
use App\Repository\CustomerRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;

class TicketManager
{
    private ?Ticket $ticket;
    private ?int $flightStatus;
    private ?int $ticketStatus;
    private ?int $customerId;
    private ?int $ticketCustomerId;
    private EntityManagerInterface $entityManager;
    private TicketRepository $ticketRepository;
    private CustomerRepository $customerRepository;

    public function __construct(EntityManagerInterface $entityManager, TicketRepository $ticketRepository, CustomerRepository $customerRepository)
    {
        $this->entityManager = $entityManager;
        $this->ticketRepository = $ticketRepository;
        $this->customerRepository = $customerRepository;
    }

    public function reservePlace(int $ticketId, int $customerId): bool
    {
        $this->init($ticketId, $customerId);
        if ($this->canBeReserved()) {
            $this->updateTicket(Ticket::IS_RESERVED, $customerId);
            return true;
        }

        return false;
    }

    public function rejectReservedPlace(int $ticketId, int $customerId): bool
    {
        $this->init($ticketId, $customerId);
        if ($this->canBeRejected()) {
            $this->updateTicket(Ticket::IS_FOR_SALE);
            return true;
        }

        return false;
    }

    public function buyTicket(int $ticketId, int $customerId): bool
    {
        $this->init($ticketId, $customerId);
        if ($this->canBeBought()) {
            $this->updateTicket(Ticket::IS_BOUGHT, $customerId);
            return true;
        }

        return false;
    }

    public function refundTicket(int $ticketId, int $customerId): bool
    {
        $this->init($ticketId, $customerId);
        if ($this->canBeRefunded()) {
            $this->updateTicket(Ticket::IS_FOR_SALE);
            return true;
        }

        return false;
    }

    private function init(int $ticketId, int $customerId)
    {
        $this->ticket = $this->ticketRepository->find($ticketId);
        $this->flightStatus = $this->ticket->getFlight()->getStatus();
        $this->ticketStatus = $this->ticket->getStatus();
        $customer = $this->ticket->getCustomer();
        if (!is_null($customer)) {
            $this->ticketCustomerId = $customer->getId();
        }
        $this->customerId = $customerId;
    }

    private function canBeReserved(): bool
    {
        return ($this->flightStatus === Flight::SALE_IS_OPEN && $this->ticketStatus === Ticket::IS_FOR_SALE);
    }

    private function canBeRejected(): bool
    {
        return ($this->ticketStatus === Ticket::IS_RESERVED && $this->ticketCustomerId === $this->customerId);
    }

    private function canBeBought(): bool
    {
        return ($this->flightStatus === Flight::SALE_IS_OPEN &&
            ($this->ticketStatus === Ticket::IS_FOR_SALE || ($this->ticketStatus === Ticket::IS_RESERVED &&
                    $this->ticketCustomerId === $this->customerId)));
    }

    private function canBeRefunded(): bool
    {
        return ($this->ticketStatus === Ticket::IS_BOUGHT && $this->ticketCustomerId === $this->customerId);
    }

    private function updateTicket(int $status, ?int $customerId = null)
    {
        $this->ticket->setStatus($status);
        if (is_null($customerId)) {
            $customer = null;
        } else {
            $customer = $this->customerRepository->find($customerId);
        }
        $this->ticket->setCustomer($customer);
        $this->entityManager->persist($this->ticket);
        $this->entityManager->flush();
    }
}
