<?php

declare(strict_types=1);

namespace Facturapi\Resources;

use Facturapi\Http\FacturapiHttpClient;
use Facturapi\Exceptions\FacturapiException;

class NominaCatalogs {
	public function __construct(private readonly FacturapiHttpClient $client) {}

    public function searchDeductions(?array $params = null): mixed
    {
        return $this->search('deductions', $params);
    }

    public function searchPerceptions(?array $params = null): mixed
    {
        return $this->search('perceptions', $params);
    }

    private function search(string $path, $params): mixed
    {
        return $this->client->get('catalogs/nomina/' . $path, $params);
    }
}
