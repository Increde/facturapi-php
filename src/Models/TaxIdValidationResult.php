<?php
declare(strict_types=1);
namespace Facturapi\Models;

readonly class TaxIdValidationResult
{
    public function __construct(

    public ?array $efos = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['efos'] ?? []
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'efos' => $this->efos
        ], fn($v) => $v !== null);
    }
}
