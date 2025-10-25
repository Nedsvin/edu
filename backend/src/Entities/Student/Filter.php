<?php

namespace App\Entities\Student;

class Filter
{
    public static function allowedFields(): array
    {
        return ['id', 'name'];
    }
}
