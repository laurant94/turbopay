<?php

namespace App\Http\Enums;

enum TransactionStatusEnum: string
{
    case Pending             = 'pending';
    case Successful          = 'successful';
    case Failed              = 'failed';
    case Refunded            = 'refunded';
    case PartiallyRefunded   = 'partially_refunded';
    case ManualReview        = 'manual_review';

    public function label(): string
    {
        return match ($this) {
            self::Pending            => 'En attente',
            self::Successful         => 'Réussi',
            self::Failed             => 'Échoué',
            self::Refunded           => 'Remboursé',
            self::PartiallyRefunded  => 'Partiellement remboursé',
            self::ManualReview       => 'Vérification manuelle',
        };
    }

    // (OPTIONNEL) si un jour tu veux retrouver l’enum à partir du label
    public static function fromLabel(string $label): ?self
    {
        return match (strtolower($label)) {
            'en attente'                  => self::Pending,
            'réussi', 'reussi'            => self::Successful,
            'échoué', 'echoue', 'échouée' => self::Failed,
            'remboursé', 'rembourse'      => self::Refunded,
            'partiellement remboursé',
            'partiellement rembourse'     => self::PartiallyRefunded,
            'vérification manuelle',
            'verification manuelle'       => self::ManualReview,
            default                       => null,
        };
    }
}
