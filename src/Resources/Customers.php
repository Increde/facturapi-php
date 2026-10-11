<?php

declare(strict_types=1);

namespace Facturapi\Resources;

use Facturapi\Http\FacturapiHttpClient;
use Facturapi\Exceptions\FacturapiException;

class Customers {
	public function __construct(private readonly FacturapiHttpClient $client) {}

	/**
	 * Get all Customers
	 *
	 * @param array|null $params Search parameters.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function all(?array $params = null): mixed {
		return $this->client->get('customers', $params);
	}

	/**
	 * Get a Customer by ID
	 *
	 * @param string $id Customer ID.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function retrieve( $id ): mixed {
		return $this->client->get('customers/' . $id);
	}

	/**
	 * Create a Customer in your organization
	 *
	 * @param array $data Customer data.
	 * @param array|null $params Optional query parameters.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function create($data, ?array $params = null): mixed {
		return $this->client->post('customers', $data);
	}


	/**
	 * Update a Customer in your organization
	 *
	 * @param string $id Customer ID.
	 * @param array $data Customer data.
	 * @param array|null $params Optional query parameters.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function update( $id, $data, $params = null ): mixed {
		return $this->client->put('customers/' . $id, $data);
	}

	/**
	 * Delete a Customer in your organization
	 *
	 * @param string $id Customer ID.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function delete( $id ): mixed {
		return $this->client->delete('customers/' . $id);
	}

	/**
	 * Validates that a Customer's tax info is still valid
	 *
	 * @param string $id Customer ID.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function validateTaxInfo( $id ): mixed {
		return $this->client->get('customers/' . $id . "/tax-info-validation");
	}

}
