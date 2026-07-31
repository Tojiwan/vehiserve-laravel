<?php

namespace App\Enums;

enum TravelRequestStatus: string
{
    case PENDING_DEAN = 'Pending Dean';
    case APPROVED_DEAN = 'Approved by Dean';
    case REJECTED_DEAN = 'Rejected by Dean';
    case PENDING_VP = 'Pending VP';
    case APPROVED_VP = 'Approved by VP';
    case REJECTED_VP = 'Rejected by VP';
    case PENDING_SUC = 'Pending SUC';
    case APPROVED_SUC = 'Approved by SUC';
    case REJECTED_SUC = 'Rejected by SUC';
    case PENDING_MOTOR_POOL = 'Pending Motor Pool';
    case VEHICLE_AVAILABLE = 'Vehicle Available';
    case NO_VEHICLE_AVAILABLE = 'No Vehicle Available';
    case COMPLETED = 'Completed';
    case CANCELLED = 'Cancelled by User';
    case REJECTED = 'Rejected';

    public function label(): string
    {
        return $this->value;
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING_DEAN, self::PENDING_VP, self::PENDING_SUC, self::PENDING_MOTOR_POOL => 'warning',
            self::APPROVED_DEAN, self::APPROVED_VP, self::APPROVED_SUC, self::VEHICLE_AVAILABLE, self::COMPLETED => 'success',
            self::REJECTED_DEAN, self::REJECTED_VP, self::REJECTED_SUC, self::NO_VEHICLE_AVAILABLE, self::REJECTED => 'danger',
            self::CANCELLED => 'secondary',
        };
    }

    public static function approverRoles(): array
    {
        return [
            self::PENDING_DEAN => 'Dean',
            self::PENDING_VP => 'Vice President',
            self::PENDING_SUC => 'SUC President',
            self::PENDING_MOTOR_POOL => 'Motor Pool',
        ];
    }

    public function getStepIndex(): int
    {
        return match ($this) {
            self::PENDING_DEAN, self::APPROVED_DEAN, self::REJECTED_DEAN => 0,
            self::PENDING_VP, self::APPROVED_VP, self::REJECTED_VP => 1,
            self::PENDING_SUC, self::APPROVED_SUC, self::REJECTED_SUC => 2,
            self::PENDING_MOTOR_POOL, self::VEHICLE_AVAILABLE, self::NO_VEHICLE_AVAILABLE => 3,
            default => 0,
        };
    }

    public function isRejected(): bool
    {
        return in_array($this, [
            self::REJECTED_DEAN,
            self::REJECTED_VP,
            self::REJECTED_SUC,
            self::NO_VEHICLE_AVAILABLE,
            self::REJECTED,
        ]);
    }

    public function nextStatus(bool $approved): ?self
    {
        if (!$approved) {
            return self::REJECTED;
        }

        return match ($this) {
            self::PENDING_DEAN => self::PENDING_VP,
            self::PENDING_VP => self::PENDING_SUC,
            self::PENDING_SUC => self::PENDING_MOTOR_POOL,
            self::PENDING_MOTOR_POOL => self::VEHICLE_AVAILABLE,
            self::VEHICLE_AVAILABLE => self::COMPLETED,
            default => null,
        };
    }
}