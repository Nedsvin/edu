<?php

namespace App\Entities\User;

enum Role: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}
