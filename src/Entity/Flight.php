<?php

namespace App\Entity;

use App\Repository\FlightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PostPersist;

/**
 * @ORM\Entity(repositoryClass=FlightRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Flight
{
    public const SALE_IS_OPEN = 0;
    public const SALE_IS_COMPLETE = 1;
    public const IS_CANCELLED = 2;
    public const FIRST_PLACE_NUMBER = 1;
    public const LAST_PLACE_NUMBER = 150;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="flight", cascade={"persist"})
     */
    private $tickets;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /** @PostPersist */
    public function createTickets()
    {
        $this->tickets = new ArrayCollection();
        for ($i = self::FIRST_PLACE_NUMBER; $i <= self::LAST_PLACE_NUMBER; $i++) {
            $ticket = new Ticket();
            $ticket->setFlight($this);
            $ticket->setStatus(Ticket::IS_FOR_SALE);
            $ticket->setPlace($i);
            $this->tickets->add($ticket);
        }
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setFlight($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getFlight() === $this) {
                $ticket->setFlight(null);
            }
        }

        return $this;
    }
}
