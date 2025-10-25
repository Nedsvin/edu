<?php

namespace App\Entities\Registration;

class Filter
{
    public static function allowedFields(): array
    {
        return ['student_id', 'class_id', 'date_registration'];
    }
}
