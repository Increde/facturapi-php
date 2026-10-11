<?php
declare(strict_types=1);
namespace Facturapi\Models;

readonly class NominaPercepcionNestedInput
{
    public function __construct(

    public ?\Facturapi\Models\NominaAccionesInput $acciones_o_titulos = null,

    /** @var \Facturapi\Models\NominaHorasExtraInput[]|null */
    public ?array $horas_extra = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['acciones_o_titulos']) && is_array($data['acciones_o_titulos']) ? \Facturapi\Models\NominaAccionesInput::fromArray($data['acciones_o_titulos']) : null,
            isset($data['horas_extra']) && is_array($data['horas_extra']) ? array_map(fn($i) => is_array($i) ? \Facturapi\Models\NominaHorasExtraInput::fromArray($i) : $i, $data['horas_extra']) : []
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'acciones_o_titulos' => $this->acciones_o_titulos !== null ? $this->acciones_o_titulos->toArray() : null,
            'horas_extra' => $this->horas_extra !== null ? array_map(fn($i) => $i->toArray(), $this->horas_extra) : null
        ], fn($v) => $v !== null);
    }
}
