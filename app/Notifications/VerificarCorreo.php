<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerificarCorreo extends Notification
{
    use Queueable;

    public $email;
    public $nombre;
    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email,$nombre,$token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = urlencode($token);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bienvenido a nuestro portal de becas')
            ->greeting('Hola, '.$this->nombre)
            ->salutation("Equipo del CFC")
            ->line('Este es un correo de verificación para que empieces a usar el sistema de postulación online de las becas del CFC. Presioná el botón para que verifiquemos tu dirección y puedas postularte. ')
            ->action('Verificar mi correo', url('/email/verify/'.$this->token))
            ->line('Gracias por registrarte,')
            ->from('becas@cfcultura.com.ar','CFC - Usuario Registrado');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
