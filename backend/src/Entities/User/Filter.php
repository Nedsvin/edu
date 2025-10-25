<?php

namespace App\Entities\User;

class Filter
{
    public static function allowedFields(): array
    {
        return ['id', 'name'];
    }
}
