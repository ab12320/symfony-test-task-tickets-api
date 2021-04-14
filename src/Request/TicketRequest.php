<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class TicketRequest implements RequestDTOInterface
{
    /**
     * @Assert\Positive()
     */
    private int $ticketId;
    /**
     * @Assert\Positive()
     */
    private int $customerId;

    public function __construct(Request $request)
    {
        $this->ticketId = (int) $request->query->get('ticketId');
        $this->customerId = (int) $request->query->get('customerId');
    }

    public function getTicketId(): int
    {
        return $this->ticketId;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }
}
