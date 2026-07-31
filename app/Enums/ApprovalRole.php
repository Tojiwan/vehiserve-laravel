<?php

namespace App\Enums;

enum ApprovalRole: string
{
    case MOTOR_POOL = 'Motor Pool';
    case DEAN = 'Dean';
    case VICE_PRESIDENT = 'Vice President';
    case SUC_PRESIDENT = 'SUC President';

    public function label(): string
    {
        return $this->value;
    }

    public function permission(): string
    {
        return match ($this) {
            self::MOTOR_POOL => 'approve vehicle requests',
            self::DEAN => 'approve vehicle requests',
            self::VICE_PRESIDENT => 'approve vehicle requests',
            self::SUC_PRESIDENT => 'approve vehicle requests',
        };
    }

    public function travelPermission(): string
    {
        return match ($this) {
            self::DEAN => 'approve travel requests',
            self::VICE_PRESIDENT => 'approve travel requests',
            self::SUC_PRESIDENT => 'approve travel requests',
            self::MOTOR_POOL => 'approve travel requests',
        };
    }

    public function order(): int
    {
        return match ($this) {
            self::MOTOR_POOL => 1,
            self::DEAN => 2,
            self::VICE_PRESIDENT => 3,
            self::SUC_PRESIDENT => 4,
        };
    }
}