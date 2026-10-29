<?php

final class PaymentMethod
{
    private const LABELS = [
        'credit_card' => 'Cartão de crédito',
        'debit_card' => 'Cartão de débito',
        'pix' => 'Pix',
        'cash' => 'Dinheiro',
        'boleto' => 'Boleto',
    ];

    public static function options(): array
    {
        return self::LABELS;
    }

    public static function labelFor(string $method): string
    {
        return self::LABELS[$method] ?? 'Forma de pagamento desconhecida';
    }
}