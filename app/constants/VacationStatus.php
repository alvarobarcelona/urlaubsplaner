<?php

namespace constants;

class VacationStatus
{
    const APPROVED = 1;
    const PENDING = 2;
    const REJECTED = 3;

    public static function toLabel(int $status): string
    {
        return match ($status) {
            self::APPROVED => 'Approved',
            self::PENDING => 'Pending',
            self::REJECTED => 'Rejected',
            default => 'Unknown',
        };
    }

    public static function all(): array
    {
        return [
            self::APPROVED => 'Approved',
            self::PENDING => 'Pending',
            self::REJECTED => 'Rejected',
        ];
    }

}