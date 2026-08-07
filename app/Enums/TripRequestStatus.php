<?php

namespace App\Enums;

enum TripRequestStatus: string
{
    case PENDING_DEAN = 'Pending Dean';
    case APPROVED_DEAN = 'Approved by Dean';
    case REJECTED_DEAN = 'Rejected by Dean';
    case PENDING_VP = 'Pending Vice President';
    case APPROVED_VP = 'Approved by Vice President';
    case REJECTED_VP = 'Rejected by Vice President';
    case PENDING_SUC = 'Pending SUC President';
    case APPROVED_SUC = 'Approved by SUC President';
    case REJECTED_SUC = 'Rejected by SUC President';
    case PENDING_MOTOR_POOL = 'Pending Motor Pool';
    case VEHICLE_ASSIGNED = 'Vehicle Assigned';
    case NO_VEHICLE_AVAILABLE = 'No Vehicle Available';
    case PENDING_FINAL_MP = 'Pending Final MP Approval';
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
            self::PENDING_DEAN, self::PENDING_VP, self::PENDING_SUC, self::PENDING_MOTOR_POOL, self::PENDING_FINAL_MP => 'warning',
            self::APPROVED_DEAN, self::APPROVED_VP, self::APPROVED_SUC, self::VEHICLE_ASSIGNED, self::COMPLETED => 'success',
            self::NO_VEHICLE_AVAILABLE, self::REJECTED_DEAN, self::REJECTED_VP, self::REJECTED_SUC, self::REJECTED => 'danger',
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
            self::PENDING_FINAL_MP => 'Motor Pool',
        ];
    }

    public function getStepIndex(): int
    {
        return match ($this) {
            self::PENDING_DEAN => 0,
            self::APPROVED_DEAN, self::REJECTED_DEAN => 1,
            self::PENDING_VP => 1,
            self::APPROVED_VP, self::REJECTED_VP => 2,
            self::PENDING_SUC => 2,
            self::APPROVED_SUC, self::REJECTED_SUC => 3,
            self::PENDING_MOTOR_POOL => 3,
            self::VEHICLE_ASSIGNED, self::COMPLETED => 5,
            self::NO_VEHICLE_AVAILABLE => 4,
            self::PENDING_FINAL_MP => 4,
            default => 0,
        };
    }

    public function outcomeLabel(): ?string
    {
        return match ($this) {
            self::VEHICLE_ASSIGNED, self::COMPLETED => 'Approved',
            self::NO_VEHICLE_AVAILABLE,
            self::REJECTED_DEAN,
            self::REJECTED_VP,
            self::REJECTED_SUC,
            self::REJECTED => 'Declined',
            default => null,
        };
    }

    public function isRejected(): bool
    {
        return in_array($this, [
            self::NO_VEHICLE_AVAILABLE,
            self::REJECTED_DEAN,
            self::REJECTED_VP,
            self::REJECTED_SUC,
            self::REJECTED,
        ]);
    }

    public function getRejectedStepIndex(): ?int
    {
        return $this->isRejected() ? $this->getStepIndex() : null;
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
            self::PENDING_MOTOR_POOL => self::VEHICLE_ASSIGNED,
            self::VEHICLE_ASSIGNED => self::COMPLETED,
            default => null,
        };
    }
}