<?php

declare(strict_types=1);

namespace Facturapi\Models;

readonly class SuccessResponse
{
    public function __construct(
        public bool $success = false,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['success'] ?? false
        );
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success
        ];
    }
}
