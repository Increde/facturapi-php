<?php
declare(strict_types=1);
namespace Facturapi\Models;

readonly class IepsTax
{
    public function __construct(

    public float $rate,

    public ?float $base = null,

    public ?string $type = null,

    public ?string $factor = null,

    public ?bool $withholding = null,

    public ?string $ieps_mode = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['rate'] ?? 0,
            $data['base'] ?? 0,
            $data['type'] ?? null,
            $data['factor'] ?? null,
            $data['withholding'] ?? false,
            $data['ieps_mode'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'rate' => $this->rate,
            'base' => $this->base,
            'type' => $this->type,
            'factor' => $this->factor,
            'withholding' => $this->withholding,
            'ieps_mode' => $this->ieps_mode
        ], fn($v) => $v !== null);
    }
}
