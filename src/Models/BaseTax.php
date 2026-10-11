<?php
declare(strict_types=1);
namespace Facturapi\Models;

readonly class BaseTax
{
    public function __construct(

    public float $rate,

    public ?float $base = null,

    public ?string $type = null,

    public ?string $factor = null,

    public ?bool $withholding = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['rate'] ?? 0,
            $data['base'] ?? 0,
            $data['type'] ?? null,
            $data['factor'] ?? null,
            $data['withholding'] ?? false
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'rate' => $this->rate,
            'base' => $this->base,
            'type' => $this->type,
            'factor' => $this->factor,
            'withholding' => $this->withholding
        ], fn($v) => $v !== null);
    }
}
