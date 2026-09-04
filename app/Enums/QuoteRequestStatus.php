<?php

namespace App\Enums;

enum QuoteRequestStatus: string
{
    case New = 'nouveau';
    case Qualified = 'qualifie';
    case UnderReview = 'en_etude';
    case QuotePrepared = 'devis_prepare';
    case QuoteSent = 'devis_envoye';
    case Negotiating = 'negociation';
    case Won = 'gagne';
    case Lost = 'perdu';
    case Archived = 'archive';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Nouveau',
            self::Qualified => 'Qualifié',
            self::UnderReview => 'En étude',
            self::QuotePrepared => 'Devis préparé',
            self::QuoteSent => 'Devis envoyé',
            self::Negotiating => 'En négociation',
            self::Won => 'Gagné / Signé',
            self::Lost => 'Perdu',
            self::Archived => 'Archivé',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::New => 'primary',
            self::Qualified => 'accent',
            self::UnderReview => 'warning',
            self::QuotePrepared => 'info',
            self::QuoteSent => 'sky',
            self::Negotiating => 'amber',
            self::Won => 'success',
            self::Lost => 'danger',
            self::Archived => 'zinc',
        };
    }
}
