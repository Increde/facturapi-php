<?php

declare(strict_types=1);

namespace Facturapi\Resources;

use Facturapi\Http\FacturapiHttpClient;
use Facturapi\Exceptions\FacturapiException;

class Tools {
	public function __construct(private readonly FacturapiHttpClient $client) {}

	/**
	 * Validates a tax id
	 *
	 * @param string $tax_id Tax ID (RFC) to validate.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function validateTaxId($tax_id): mixed
	{
		return $this->client->get('tools/tax_id_validation', ['tax_id' => $tax_id]);
	}

	/**
	 * Health check (Pulse)
	 *
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function check(): mixed
	{
		return $this->client->get('check');
	}
}
