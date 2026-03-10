<?php

namespace App\Enum;

enum TransactionType: string
{
    case Payment = 'payment';  
    case TopUp = 'top up';      
    case Refund = 'refund';    
    
   
    public function label(): string
    {
        return match($this) {
            self::Payment => 'Paiement',
            self::TopUp => 'Rechargement',
            self::Refund => 'Remboursement',
        };
    }
}