<?php

declare(strict_types=1);

namespace Facturapi\Models;

readonly class GenericError
{
    public function __construct(
        public string $message,
        public int $status,
        public bool $ok,
        public string $code,
        public ?string $location = null,
        public ?string $path = null,

        /** @var \Facturapi\Models\ErrorDetail[]|null */
        public ?array $errors = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['message'] ?? '',
            $data['status'] ?? 0,
            $data['ok'] ?? false,
            $data['code'] ?? '',
            $data['location'] ?? null,
            $data['path'] ?? null,
            isset($data['errors']) && is_array($data['errors']) ? array_map(fn ($i) => is_array($i) ? \Facturapi\Models\ErrorDetail::fromArray($i) : $i, $data['errors']) : []
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'message' => $this->message,
            'status' => $this->status,
            'ok' => $this->ok,
            'code' => $this->code,
            'location' => $this->location,
            'path' => $this->path,
            'errors' => $this->errors !== null ? array_map(fn ($i) => $i->toArray(), $this->errors) : null
        ], fn ($v) => $v !== null);
    }
}
