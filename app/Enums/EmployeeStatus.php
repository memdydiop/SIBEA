<?php

namespace App\Enums;

enum EmployeeStatus: string
{
    case Active = 'actif';
    case OnLeave = 'en_conge';
    case Suspended = 'suspendu';
    case Terminated = 'sorti';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Actif',
            self::OnLeave => 'En congé',
            self::Suspended => 'Suspendu',
            self::Terminated => 'Sorti',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::OnLeave => 'warning',
            self::Suspended => 'danger',
            self::Terminated => 'zinc',
        };
    }
}
