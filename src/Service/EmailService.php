<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Reservation;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;

class EmailService
{
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function sendEmail(string $to, string $subject, string $content): void
    {
        $email = (new Email())
            ->from('your-email@gmail.com') // Replace with your sender email
            ->to($to)
            ->subject($subject)
            ->html($content);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new \Exception('Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }
    }

    public function sendReservationConfirmationEmail(Reservation $reservation): void
    {
        $to = $reservation->getEmail(); 
        $subject = "Confirmation de votre réservation - " . $reservation->getOffre()->getTitre();
        $content = $this->getReservationConfirmationTemplate($reservation);

        $this->sendEmail($to, $subject, $content);
    }

    private function getReservationConfirmationTemplate(Reservation $reservation): string
    {
        $offre = $reservation->getOffre();
        return "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #1a237e; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; }
                    .details { background-color: #f5f5f5; padding: 15px; margin: 20px 0; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Confirmation de réservation</h1>
                    </div>
                    <div class='content'>
                        <p>Bonjour,</p>
                        <p>Votre réservation pour <strong>{$offre->getTitre()}</strong> a été confirmée.</p>
                        
                        <div class='details'>
                            <h2>Détails de la réservation :</h2>
                            <ul>
                                <li>Numéro de réservation : {$reservation->getId()}</li>
                                <li>Nombre de personnes : {$reservation->getNombrePersonne()}</li>
                                <li>Prix total : {$reservation->getPrixTotal()} €</li>
                                <li>Mode de paiement : {$reservation->getModePaiement()}</li>
                            </ul>
                        </div>
                        
                        <p>Merci de votre confiance !</p>
                        
                        <div style='text-align: center; margin-top: 20px;'>
                            <p>L'équipe Wonderwise</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ";
    }
}
