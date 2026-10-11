<?php

declare(strict_types=1);

namespace Facturapi\Resources;

use Facturapi\Http\FacturapiHttpClient;
use Facturapi\Exceptions\FacturapiException;

class Products {
	public function __construct(private readonly FacturapiHttpClient $client) {}


	/**
	 * Get all Products
	 *
	 * @param array|null $params Search parameters.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function all(?array $params = null): mixed {
		return $this->client->get('products', $params);
	}

	/**
	 * Get a Product by ID
	 *
	 * @param string $id Product ID.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function retrieve( $id ): mixed {
		return $this->client->get('products/' . $id);
	}

	/**
	 * Create a Product in your organization
	 *
	 * @param array $params Product data.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function create( $params ): mixed {
		return $this->client->post('products', $params);
	}


	/**
	 * Update a Product in your organization
	 *
	 * @param string $id Product ID.
	 * @param array $params Product data.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function update( $id, $params ): mixed {
		return $this->client->put('products/' . $id, $params);
	}

	/**
	 * Delete a Product in your organization
	 *
	 * @param string $id Product ID.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function delete( $id ): mixed {
		return $this->client->delete('products/' . $id);
	}

}
