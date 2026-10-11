<?php
declare(strict_types=1);
namespace Facturapi\Models;

readonly class ComercioExteriorDestinatario
{
    public function __construct(

    /** @var \Facturapi\Models\ComercioExteriorDomicilio[]|null */
    public array $Domicilio,

    public ?string $NumRegIdTrib = null,

    public ?string $Nombre = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['Domicilio']) && is_array($data['Domicilio']) ? array_map(fn($i) => is_array($i) ? \Facturapi\Models\ComercioExteriorDomicilio::fromArray($i) : $i, $data['Domicilio']) : [],
            $data['NumRegIdTrib'] ?? null,
            $data['Nombre'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'Domicilio' => $this->Domicilio !== null ? array_map(fn($i) => $i->toArray(), $this->Domicilio) : null,
            'NumRegIdTrib' => $this->NumRegIdTrib,
            'Nombre' => $this->Nombre
        ], fn($v) => $v !== null);
    }
}
