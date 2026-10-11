<?php

declare(strict_types=1);

namespace Facturapi\Resources;

use Facturapi\Http\FacturapiHttpClient;
use Facturapi\Exceptions\FacturapiException;

class Retentions {
	public function __construct(private readonly FacturapiHttpClient $client) {}


	/**
	 * Search Retentions
	 *
	 * @param array|null $params Search parameters.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function all(?array $params = null): mixed
	{
		return $this->client->get('retentions', $params);
	}

	/**
	 * Get a Retention by ID
	 *
	 * @param string $id Retention ID.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function retrieve($id): mixed
	{
		return $this->client->get('retentions/' . $id);
	}

	/**
	 * Creates a Retention for the organization
	 *
	 * @param array $params Retention payload.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function create($params): mixed
	{
		return $this->client->post('retentions', $params);
	}


	/**
	 * Cancels a Retention
	 *
	 * @param string $id Retention ID.
	 * @param array|null $query URL query parameters.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function cancel($id, ?array $query = null): mixed
	{
		return $this->client->delete('retentions/' . $id, $query);
	}

	/**
	 * Updates a Retention with "draft" status
	 *
	 * @param string $id Retention ID.
	 * @param array $body Draft payload.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function updateDraft($id, $body): mixed
	{
		return $this->client->put('retentions/' . $id, $body);
	}

	/**
	 * @deprecated Use updateDraft() instead. Will be removed in v5.
	 */
	public function update_draft($id, $body): mixed
	{
		trigger_error('Retentions::update_draft() is deprecated and will be removed in v5. Use updateDraft() instead.', E_USER_DEPRECATED);
		return $this->updateDraft($id, $body);
	}

	/**
	 * Creates a new draft Retention copying the information from the specified Retention
	 *
	 * @param string $id Retention ID.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function copyToDraft($id): mixed
	{
		return $this->client->post('retentions/' . $id . "/copy", []);
	}

	/**
	 * @deprecated Use copyToDraft() instead. Will be removed in v5.
	 */
	public function copy_to_draft($id): mixed
	{
		trigger_error('Retentions::copy_to_draft() is deprecated and will be removed in v5. Use copyToDraft() instead.', E_USER_DEPRECATED);
		return $this->copyToDraft($id);
	}

	/**
	 * Stamps a draft Retention
	 *
	 * @param string $id Retention ID.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function stampDraft($id): mixed
	{
		return $this->client->post('retentions/' . $id . "/stamp", []);
	}

	/**
	 * @deprecated Use stampDraft() instead. Will be removed in v5.
	 */
	public function stamp_draft($id): mixed
	{
		trigger_error('Retentions::stamp_draft() is deprecated and will be removed in v5. Use stampDraft() instead.', E_USER_DEPRECATED);
		return $this->stampDraft($id);
	}

	/**
	 * Sends the retention to the customer's email
	 *
	 * @param string $id Retention ID.
	 * @param string|array|null $email Email or list of emails.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function sendByEmail($id, ?array $email = null): mixed
	{
		return $this->client->post('retentions/' . $id . "/email", ["email" => $email]);
	}

	/**
	 * @deprecated Use sendByEmail() instead. Will be removed in v5.
	 */
	public function send_by_email($id, ?array $email = null): mixed
	{
		trigger_error('Retentions::send_by_email() is deprecated and will be removed in v5. Use sendByEmail() instead.', E_USER_DEPRECATED);
		return $this->sendByEmail($id, $email);
	}

	/**
	 * Downloads the specified Retention in a ZIP package containing both PDF and XML files
	 *
	 * @param string $id Retention ID.
	 * @return string ZIP file contents.
	 *
	 * @throws FacturapiException
	 */
	public function downloadZip($id): string
	{
		return $this->client->download('retentions/' . $id . "/zip");
	}

	/**
	 * @deprecated Use downloadZip() instead. Will be removed in v5.
	 */
	public function download_zip($id): string
	{
		trigger_error('Retentions::download_zip() is deprecated and will be removed in v5. Use downloadZip() instead.', E_USER_DEPRECATED);
		return $this->downloadZip($id);
	}

	/**
	 * Downloads the specified Retention in a PDF file
	 *
	 * @param string $id Retention ID.
	 * @return string Raw PDF bytes (binary string, not base64-encoded).
	 *
	 * @throws FacturapiException
	 */
	public function downloadPdf($id): string
	{
		return $this->client->download('retentions/' . $id . "/pdf");
	}

	/**
	 * @deprecated Use downloadPdf() instead. Will be removed in v5.
	 */
	public function download_pdf($id): string
	{
		trigger_error('Retentions::download_pdf() is deprecated and will be removed in v5. Use downloadPdf() instead.', E_USER_DEPRECATED);
		return $this->downloadPdf($id);
	}

	/**
	 * Downloads the specified Retention in a XML file
	 *
	 * @param string $id Retention ID.
	 * @return string XML file contents.
	 *
	 * @throws FacturapiException
	 */
	public function downloadXml($id): string
	{
		return $this->client->download('retentions/' . $id . "/xml");
	}

	/**
	 * @deprecated Use downloadXml() instead. Will be removed in v5.
	 */
	public function download_xml($id): string
	{
		trigger_error('Retentions::download_xml() is deprecated and will be removed in v5. Use downloadXml() instead.', E_USER_DEPRECATED);
		return $this->downloadXml($id);
	}
}
