<?php
declare(strict_types=1);
namespace Facturapi\Models;

readonly class NominaPercepcionesInput
{
    public function __construct(

    /** @var \Facturapi\Models\NominaPercepcionInput[]|null */
    public array $percepcion,

    public ?\Facturapi\Models\NominaJubilacionInput $jubilacion_pension_retiro = null,

    public ?\Facturapi\Models\NominaSeparacionInput $separacion_indemnizacion = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['percepcion']) && is_array($data['percepcion']) ? array_map(fn($i) => is_array($i) ? \Facturapi\Models\NominaPercepcionInput::fromArray($i) : $i, $data['percepcion']) : [],
            isset($data['jubilacion_pension_retiro']) && is_array($data['jubilacion_pension_retiro']) ? \Facturapi\Models\NominaJubilacionInput::fromArray($data['jubilacion_pension_retiro']) : null,
            isset($data['separacion_indemnizacion']) && is_array($data['separacion_indemnizacion']) ? \Facturapi\Models\NominaSeparacionInput::fromArray($data['separacion_indemnizacion']) : null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'percepcion' => $this->percepcion !== null ? array_map(fn($i) => $i->toArray(), $this->percepcion) : null,
            'jubilacion_pension_retiro' => $this->jubilacion_pension_retiro !== null ? $this->jubilacion_pension_retiro->toArray() : null,
            'separacion_indemnizacion' => $this->separacion_indemnizacion !== null ? $this->separacion_indemnizacion->toArray() : null
        ], fn($v) => $v !== null);
    }
}
