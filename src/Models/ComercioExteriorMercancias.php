<?php
declare(strict_types=1);
namespace Facturapi\Models;

readonly class ComercioExteriorMercancias
{
    public function __construct(

    /** @var \Facturapi\Models\ComercioExteriorMercancia[]|null */
    public array $Mercancia,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['Mercancia']) && is_array($data['Mercancia']) ? array_map(fn($i) => is_array($i) ? \Facturapi\Models\ComercioExteriorMercancia::fromArray($i) : $i, $data['Mercancia']) : []
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'Mercancia' => $this->Mercancia !== null ? array_map(fn($i) => $i->toArray(), $this->Mercancia) : null
        ], fn($v) => $v !== null);
    }
}
