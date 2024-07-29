<?php

namespace App\Mail;

use App\Models\Information;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

/**
 * Classe pour envoyer un email d'information à l'utilisateur.
 */
class UserEmailInformation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Information $information,
    ) {}


    /**
     * Récupère la définition du contenu du message.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.useremail',
            with: [
                'contenuMessage' => $this->information->contenu_message
            ]
        );
    }

}
