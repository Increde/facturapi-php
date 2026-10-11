<?php

declare(strict_types=1);

namespace Facturapi\Models;

readonly class LocalTax
{
    public function __construct(
        public float $rate,
        public ?float $base = null,
        public string $type,
        public ?bool $withholding = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['rate'] ?? 0,
            $data['base'] ?? 0,
            $data['type'] ?? '',
            $data['withholding'] ?? false
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'rate' => $this->rate,
            'base' => $this->base,
            'type' => $this->type,
            'withholding' => $this->withholding
        ], fn ($v) => $v !== null);
    }
}
