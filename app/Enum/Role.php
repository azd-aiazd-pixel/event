<?php

namespace App\Enum;


enum Role: string
{
    
    case Admin = 'admin';
    case Store = 'store'; 
    case Participant = 'participant';

 
    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }

    public function isStore(): bool
    {
        return $this === self::Store;
    }

    public function isParticipant(): bool
    {
        return $this === self::Participant;
    }
}