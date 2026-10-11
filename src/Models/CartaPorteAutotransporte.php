<?php
declare(strict_types=1);
namespace Facturapi\Models;

readonly class CartaPorteAutotransporte
{
    public function __construct(

    public ?string $PermSCT = null,

    public ?string $NumPermisoSCT = null,

    public ?\Facturapi\Models\CartaPorteIdentificacionVehicular $IdentificacionVehicular = null,

    public ?\Facturapi\Models\CartaPorteSeguros $Seguros = null,

    /** @var \Facturapi\Models\CartaPorteRemolque[]|null */
    public ?array $Remolques = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['PermSCT'] ?? null,
            $data['NumPermisoSCT'] ?? null,
            isset($data['IdentificacionVehicular']) && is_array($data['IdentificacionVehicular']) ? \Facturapi\Models\CartaPorteIdentificacionVehicular::fromArray($data['IdentificacionVehicular']) : null,
            isset($data['Seguros']) && is_array($data['Seguros']) ? \Facturapi\Models\CartaPorteSeguros::fromArray($data['Seguros']) : null,
            isset($data['Remolques']) && is_array($data['Remolques']) ? array_map(fn($i) => is_array($i) ? \Facturapi\Models\CartaPorteRemolque::fromArray($i) : $i, $data['Remolques']) : []
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'PermSCT' => $this->PermSCT,
            'NumPermisoSCT' => $this->NumPermisoSCT,
            'IdentificacionVehicular' => $this->IdentificacionVehicular !== null ? $this->IdentificacionVehicular->toArray() : null,
            'Seguros' => $this->Seguros !== null ? $this->Seguros->toArray() : null,
            'Remolques' => $this->Remolques !== null ? array_map(fn($i) => $i->toArray(), $this->Remolques) : null
        ], fn($v) => $v !== null);
    }
}
