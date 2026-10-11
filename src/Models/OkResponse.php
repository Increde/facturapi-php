<?php

declare(strict_types=1);

namespace Facturapi\Models;

readonly class OkResponse
{
    public function __construct(
        public bool $ok,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['ok'] ?? false
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'ok' => $this->ok
        ], fn ($v) => $v !== null);
    }
}
