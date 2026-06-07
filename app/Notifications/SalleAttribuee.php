<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SalleAttribuee extends Notification
{
    use Queueable;

    public function __construct(public Reservation $reservation) {}

    // Canaux d'envoi : email + base de données (cloche)
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    // Contenu de l'email
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Salle attribuée — ' . $this->reservation->motif)
            ->greeting('Bonjour ' . $notifiable->nom . ',')
            ->line('Une salle vous a été attribuée pour votre cours.')
            ->line('**Cours :** ' . $this->reservation->motif)
            ->line('**Salle attribuée :** ' . $this->reservation->salle->nom . ' (' . $this->reservation->salle->niveau . ')')
            ->line('**Date :** ' . \Carbon\Carbon::parse($this->reservation->date_debut)->format('d/m/Y à H\hi'))
            ->line('**Heure de fin :** ' . \Carbon\Carbon::parse($this->reservation->heure_fin)->format('H\hi'))
            ->action('Voir mes réservations', route('professeur.reservations.index'))
            ->line('Cette attribution est définitive.');
    }

    // Contenu de la notification en base (cloche)
    public function toDatabase(object $notifiable): array
    {
        return [
            'message'    => 'Salle attribuée pour votre cours : ' . $this->reservation->motif,
            'salle'      => $this->reservation->salle->nom,
            'niveau'     => $this->reservation->salle->niveau,
            'date_debut' => $this->reservation->date_debut,
            'heure_fin'  => $this->reservation->heure_fin,
            'motif'      => $this->reservation->motif,
        ];
    }
}