<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario; 
    public $url;
    public $contraseñaTemporal;

    /**
     * Create a new message instance.
     */
    public function __construct($usuario, $contraseñaTemporal, $url)
    {
        $this->usuario = $usuario;
        $this->contraseñaTemporal = $contraseñaTemporal;
        $this->url = $url;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cambiar Contraseña',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
             view: 'mails.cambiarContraseña',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
{
    return $this->view('emails.password_mail')
        ->subject('Restablecimiento de Contraseña - FCT')
        ->with([
            'usuario' => $this->usuario,
            'contraseñaTemporal' => $this->contraseñaTemporal,
            'url' => $this->url,
        ])
        ->attach(public_path('images/LogoFCT_transpa.png'), [
            'as' => 'logo.png',
            'mime' => 'image/png',
        ]);
}

}
