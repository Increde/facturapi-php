<?php

declare(strict_types=1);

namespace Facturapi\Models;

readonly class ComercioExteriorReceptor
{
    public function __construct(
        public ?\Facturapi\Models\ComercioExteriorDomicilio $Domicilio = null,
        public ?string $NumRegIdTrib = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['Domicilio']) && is_array($data['Domicilio']) ? \Facturapi\Models\ComercioExteriorDomicilio::fromArray($data['Domicilio']) : null,
            $data['NumRegIdTrib'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'Domicilio' => $this->Domicilio !== null ? $this->Domicilio->toArray() : null,
            'NumRegIdTrib' => $this->NumRegIdTrib
        ], fn ($v) => $v !== null);
    }
}
