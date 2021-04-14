<?php

namespace App\MessageHandler;

use App\Message\FlightIsCancelled;
use App\Repository\TicketRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class FlightIsCancelledHandler implements MessageHandlerInterface
{
    private TicketRepository $ticketRepository;
    private MailerInterface $mailer;

    public function __construct(TicketRepository $ticketRepository, MailerInterface $mailer)
    {
        $this->ticketRepository = $ticketRepository;
        $this->mailer = $mailer;
    }

    public function __invoke(FlightIsCancelled $flightIsCancelled)
    {
        $flightId = $flightIsCancelled->getFlightId();
        $tickets = $this->ticketRepository->findByFlightId($flightId);

        $emailAlreadySent = [];
        foreach ($tickets as $ticket) {
            $to = $ticket->getCustomer()->getEmail();
            if (in_array($to, $emailAlreadySent)) continue;

//            $email = (new Email())
//                ->from('hello@example.com')
//                ->to($to)
//                ->subject('Flight #' . $flightId . ' has been cancelled!')
//                ->text('Flight #' . $flightId . ' has been cancelled!');
//
//            $this->mailer->send($email);

            $emailAlreadySent[] = $to;
        }
    }
}
