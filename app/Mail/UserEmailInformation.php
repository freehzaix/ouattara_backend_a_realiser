<?php

namespace App\Mail;

use App\Models\Information;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Classe pour envoyer un email d'information à l'utilisateur.
 */
class UserEmailInformation extends Mailable
{
    use Queueable, SerializesModels;

    
    public $data = [];

    /**
     * Crée une nouvelle instance de message.
     *
     * @param Information $information L'information à envoyer par email.
     */
    public function __construct(Information $information)
    {
        $this->data = $information;
    }

    /**
     * Récupère l'enveloppe du message.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'no-reply@monordreetmoi.com',
            subject: 'Envoi de mail d\'information',
        );
    }

    /**
     * Récupère la définition du contenu du message.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.useremail',
        );
    }

    /**
     * Récupère les pièces jointes du message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}