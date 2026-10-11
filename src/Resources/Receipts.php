<?php

declare(strict_types=1);

namespace Facturapi\Resources;

use Facturapi\Http\FacturapiHttpClient;
use Facturapi\Exceptions\FacturapiException;

class Receipts {
	public function __construct(private readonly FacturapiHttpClient $client) {}


	/**
	 * Search or list all receipts in your organization
	 *
	 * @param array|null $params Search parameters.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function all(?array $params = null): mixed
	{
		return $this->client->get('receipts', $params);
	}

	/**
	 * Get a Receipt by ID
	 *
	 * @param string $id Receipt ID.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function retrieve($id): mixed
	{
		return $this->client->get('receipts/' . $id);
	}

	/**
	 * Create a Receipt in your organization
	 *
	 * @param array $params Receipt payload.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function create($params): mixed
	{
		return $this->client->post('receipts', $params);
	}


	/**
	 * Creates an invoice for a Receipt
	 *
	 * @param string $id Receipt ID.
	 * @param array $params Invoice payload.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function invoice($id, $params): mixed
	{
		return $this->client->post('receipts/' . $id . "/invoice", $params);
	}

	/**
	 * Creates a global invoice from the open receipts in the last completed period
	 *
	 * @param array $params Global invoice payload.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function createGlobalInvoice($params): mixed
	{
		return $this->client->post('receipts' . "/global-invoice", $params);
	}

	/**
	 * Creates a single invoice using a list of receipt keys
	 *
	 * @param $data Array of properties and property values for creating an invoice from multiple receipts
	 *
	 * @return Response The created invoice object or preview summary when dry_run is enabled
	 *
	 * @throws FacturapiException
	 */
	public function toInvoice($data)
	{
		try {
			return $this->client->post('receipts' . "/to-invoice", $data);
		} catch (FacturapiException $e) {
			throw new FacturapiException('Unable to create invoice: ' . $e->getMessage());
		}
	}

	/**
	 * Generates an invoice PDF preview for multiple receipts without stamping or saving it
	 *
	 * @param array $data Array of properties and property values for generating an invoice PDF preview
	 *
	 * @return string PDF file contents
	 *
	 * @throws FacturapiException
	 */
	public function previewToInvoicePdf(array $data): string
	{
		return $this->client->postRaw('receipts' . "/to-invoice/preview", $data);
	}

	/**
	 * Cancel a Receipt
	 *
	 * @param string $id Receipt ID.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function cancel($id): mixed
	{
		return $this->client->delete('receipts/' . $id);
	}

	/**
	 * Sends the receipt to the customer's email
	 *
	 * @param string $id Receipt ID.
	 * @param string|array|null $email Email or list of emails.
	 * @return mixed JSON-decoded response.
	 *
	 * @throws FacturapiException
	 */
	public function sendByEmail($id, ?array $email = null): mixed
	{
		return $this->client->post('receipts/' . $id . "/email", $email === null ? [] : ["email" => $email]);
	}

	/**
	 * @deprecated Use sendByEmail() instead. Will be removed in v5.
	 */
	public function send_by_email($id, ?array $email = null): mixed
	{
		trigger_error('Receipts::send_by_email() is deprecated and will be removed in v5. Use sendByEmail() instead.', E_USER_DEPRECATED);
		return $this->sendByEmail($id, $email);
	}

	/**
	 * Downloads the specified receipt in a PDF file
	 *
	 * @param string $id Receipt ID.
	 * @return string Raw PDF bytes (binary string, not base64-encoded).
	 *
	 * @throws FacturapiException
	 */
	public function downloadPdf($id): string
	{
		return $this->client->download('receipts/' . $id . "/pdf");
	}

	/**
	 * @deprecated Use downloadPdf() instead. Will be removed in v5.
	 */
	public function download_pdf($id): string
	{
		trigger_error('Receipts::download_pdf() is deprecated and will be removed in v5. Use downloadPdf() instead.', E_USER_DEPRECATED);
		return $this->downloadPdf($id);
	}
}
