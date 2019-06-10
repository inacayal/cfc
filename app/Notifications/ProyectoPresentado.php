<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProyectoPresentado extends Notification
{
    use Queueable;

    public $email;
    public $nombre;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email,$nombre)
    {
        $this->email = $email;
        $this->nombre = $nombre;
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
            ->subject('Postulacion Becas CFC')
            ->greeting('Hola, '.$this->nombre)
            ->salutation("Equipo del CFC")
            ->bcc(['soporte@inacayal.com.ar','becas@cfcultura.com.ar'])
            ->line('Hemos recibido exitosamente tu postulacion y pronto sera revisada para comprobar que hayas cumplido con todos los requisitos solicitados.')
            ->action('Ir al sitio', url('/'))
            ->line('Agradecemos tu participacion. Estamos a disposicion para lo que necesites. Saludos.
                ')
            ->from('becas@cfcultura.com.ar','CFC - Postulacion exitosa');
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
