<?php

namespace App\Utils;

class DateHelper
{
    public static function formatDate(string $data): string
    {
        $dt = \DateTime::createFromFormat('d/m/Y', $data);
        return $dt ? $dt->format('Y-m-d') : $data;
    }

    public static function birthDateNow(string $data): bool
    {
        $birth = \DateTime::createFromFormat('Y-m-d', $data);
        if (!$birth) {
            return false;
        }

        $today = new \DateTime();
        $age = $today->diff($birth)->y;

        return $age >= 17;
    }
}
