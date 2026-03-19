<?php

namespace App\Enum;

enum OrderStatus: string
{
    case Pending   = 'pending';
    case Ready     = 'ready';
    case Completed = 'completed';
    case Rejected  = 'rejected';

    /**
     * Retourne un libellé lisible pour l'affichage.
     */
    public function label(): string
    {
        return match($this) {
            self::Pending   => 'En attente',
            self::Ready     => 'Prêt',
            self::Completed => 'Complété',
            self::Rejected  => 'Annulé',
        };
    }
}
