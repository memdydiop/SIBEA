<?php

namespace App\Enums;

enum ContractType: string
{
    case Cdi = 'cdi';
    case Cdd = 'cdd';
    case Interim = 'interim';
    case DailyWorker = 'journalier';
    case Subcontractor = 'sous_traitant';
    case Apprentice = 'alternant';

    public function label(): string
    {
        return match ($this) {
            self::Cdi => 'CDI',
            self::Cdd => 'CDD',
            self::Interim => 'Intérimaire',
            self::DailyWorker => 'Journalier',
            self::Subcontractor => 'Sous-traitant',
            self::Apprentice => 'Alternant / Apprenti',
        };
    }
}
