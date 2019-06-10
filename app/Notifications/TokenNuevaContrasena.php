<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TokenNuevaContrasena extends Notification
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
            ->subject('Has olvidado tu contrase���a')
            ->greeting('Hola, '.$this->nombre)
            ->salutation("Equipo del CFC")
            ->line('Elegiste reestablecer tu contrase���a en nuestra plataforma de becas. Presion��� el bot���n y ser���s redirigido a nuestra p���gina para que puedas hacerlo. ')
            ->action('Reestablecer mi contrase���a', url('contrasena/olvido/'.$this->token))
            ->line('Gracias,')
            ->from('becas@cfcultura.com.ar','CFC - Reestablecer Contrase���a');
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
