<?php

namespace App\Enums;

enum LotStatus: string
{
    case Disponible = 'disponible';
    case Option = 'option';
    case Reserve = 'reserve';
    case Vendu = 'vendu';

    public function label(): string
    {
        return match ($this) {
            self::Disponible => 'Disponible',
            self::Option => 'Option',
            self::Reserve => 'Réservé',
            self::Vendu => 'Vendu',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Disponible => 'success',
            self::Option => 'warning',
            self::Reserve => 'warning',
            self::Vendu => 'danger',
        };
    }

    /**
     * @return array<string, array<string>>
     */
    public static function transitions(): array
    {
        return [
            self::Disponible->value => [self::Option->value, self::Reserve->value, self::Vendu->value],
            self::Option->value => [self::Reserve->value, self::Disponible->value],
            self::Reserve->value => [self::Vendu->value, self::Disponible->value],
            self::Vendu->value => [],
        ];
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target->value, self::transitions()[$this->value] ?? [], true);
    }
}
