<?php
declare(strict_types=1);
namespace Facturapi\Models;

readonly class NominaDeduccionInput
{
    public function __construct(

    public string $tipo_deduccion,

    public ?string $concepto = null,

    public string $clave,

    public float $importe,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['tipo_deduccion'] ?? "",
            $data['concepto'] ?? null,
            $data['clave'] ?? "",
            $data['importe'] ?? 0
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'tipo_deduccion' => $this->tipo_deduccion,
            'concepto' => $this->concepto,
            'clave' => $this->clave,
            'importe' => $this->importe
        ], fn($v) => $v !== null);
    }
}
