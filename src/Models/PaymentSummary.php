<?php

declare(strict_types=1);

namespace Facturapi\Models;

readonly class PaymentSummary
{
    public function __construct(
        public ?string $uuid = null,
        public ?int $installment = null,
        public ?float $last_balance = null,
        public ?float $total = null,
        public ?string $currency = null,
        public ?float $amount = null,
        public ?array $taxes = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['uuid'] ?? null,
            $data['installment'] ?? null,
            (float) ($data['last_balance'] ?? 0),
            (float) ($data['total'] ?? 0),
            $data['currency'] ?? null,
            (float) ($data['amount'] ?? 0),
            $data['taxes'] ?? []
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'uuid' => $this->uuid,
            'installment' => $this->installment,
            'last_balance' => $this->last_balance,
            'total' => $this->total,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'taxes' => $this->taxes
        ], fn (int|string|float|array|null $v): bool => $v !== null);
    }
}
