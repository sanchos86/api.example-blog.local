<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    const ADMIN = 'admin';
    const SUBSCRIBER = 'subscriber';

    public static function getDefaultRole(): string
    {
        return self::SUBSCRIBER;
    }
}
