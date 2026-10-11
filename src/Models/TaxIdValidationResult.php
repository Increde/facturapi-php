<?php
declare(strict_types=1);
namespace Facturapi\Models;

readonly class TaxIdValidationResult
{
    public function __construct(
        public bool $is_valid,
        public ?array $efos = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['is_valid'] ?? false,
            $data['efos'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'is_valid' => $this->is_valid,
            'efos' => $this->efos
        ], fn($v) => $v !== null);
    }
}
