<?php

declare(strict_types=1);

namespace Facturapi\Models;

readonly class LineItem
{
    public function __construct(
        public ?float $quantity = null,
        public ?float $discount = null,
        public ?\Facturapi\Models\LineItemProduct $product = null,
        public ?\Facturapi\Models\Parts $parts = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['quantity'] ?? 0,
            $data['discount'] ?? 0,
            isset($data['product']) && is_array($data['product']) ? \Facturapi\Models\LineItemProduct::fromArray($data['product']) : null,
            isset($data['parts']) && is_array($data['parts']) ? \Facturapi\Models\Parts::fromArray($data['parts']) : null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'product' => $this->product?->toArray(),
            'parts' => $this->parts?->toArray()
        ], fn (float|array|null $v): bool => $v !== null);
    }
}
