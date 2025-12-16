<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperacionContrasenaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Recuperación de Contraseña - Ferretería San Miguel')
                    ->view('emails.recuperacion-contrasena')
                    ->with([
                        'token' => $this->token,
                        'email' => $this->email,
                        'resetUrl' => url('/restablecer-contrasena/' . $this->token)
                    ]);
    }
}