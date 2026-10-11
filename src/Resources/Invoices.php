<?php

declare(strict_types=1);

namespace Facturapi\Resources;

use Facturapi\Http\FacturapiHttpClient;
use Facturapi\Exceptions\FacturapiException;

class Invoices
{
    public function __construct(private readonly FacturapiHttpClient $client)
    {
    }


    /**
     * Get all Invoices
     *
     * @param array|null $query Query parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function all(?array $query = null): mixed
    {
        return $this->client->get('invoices', $query);
    }

    /**
     * Get an Invoice by ID
     *
     * @param string $id Invoice ID.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function retrieve($id): mixed
    {
        return $this->client->get('invoices/' . $id);
    }

    /**
     * Gets the information needed to add this invoice as a related document in a
     * payment complement (complemento de pago): the installment number according
     * to the payment history, the previous balance, and the invoice tax breakdown
     * prorated to the amount being paid.
     *
     * @param string $id Invoice ID.
     * @param float $amount Amount being paid, expressed in the invoice currency. Cannot exceed the outstanding balance.
     * @return mixed JSON-decoded related document summary.
     * @throws FacturapiException
     */
    public function paymentSummary($id, $amount): mixed
    {
        return $this->client->get('invoices/' . $id . '/payment-summary', ['amount' => $amount]);
    }

    /**
     * Create an Invoice in your organization
     *
     * @param array $body Invoice payload.
     * @param array|null $query Query parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function create($body, ?array $query = null): mixed
    {
        return $this->client->post('invoices', $body, $query);
    }


    /**
     * Cancel an Invoice in your organization
     *
     * @param string $id Invoice ID.
     * @param array $query URL query parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function cancel($id, $query): mixed
    {
        return $this->client->delete('invoices/' . $id, $query);
    }

    /**
     * Sends the invoice to the customer's email
     *
     * @param string $id Invoice ID.
     * @param string|array|null $email Email or list of emails.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function sendByEmail($id, ?array $email = null): mixed
    {
        return $this->client->post('invoices/' . $id . '/email', ['email' => $email]);
    }

    /**
     * @deprecated Use sendByEmail() instead. Will be removed in v5.
     */
    public function send_by_email($id, ?array $email = null): mixed
    {
        trigger_error('Invoices::send_by_email() is deprecated and will be removed in v5. Use sendByEmail() instead.', E_USER_DEPRECATED);
        return $this->sendByEmail($id, $email);
    }

    /**
     * Downloads the specified invoice in a ZIP package containing both PDF and XML files
     *
     * @param string $id Invoice ID.
     * @return string ZIP file contents.
     * @throws FacturapiException
     */
    public function downloadZip($id): string
    {
        return $this->client->download('invoices/' . $id . '/zip');
    }

    /**
     * @deprecated Use downloadZip() instead. Will be removed in v5.
     */
    public function download_zip($id): string
    {
        trigger_error('Invoices::download_zip() is deprecated and will be removed in v5. Use downloadZip() instead.', E_USER_DEPRECATED);
        return $this->downloadZip($id);
    }

    /**
     * Creates or retrieves an invoice ZIP request
     *
     * @param array $body ZIP request payload.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function createZipRequest($body): mixed
    {
        return $this->client->post('invoices/' . 'zip-requests', $body);
    }

    /**
     * Lists invoice ZIP requests
     *
     * @param array|null $query Query parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function listZipRequests(?array $query = null): mixed
    {
        return $this->client->get('invoices/' . 'zip-requests', $query);
    }

    /**
     * Retrieves an invoice ZIP request by ID
     *
     * @param string $id ZIP request ID.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function retrieveZipRequest($id): mixed
    {
        return $this->client->get('invoices/' . 'zip-requests/' . $id);
    }

    /**
     * Downloads a completed invoice ZIP request
     *
     * @param string $id ZIP request ID.
     * @return string ZIP file contents.
     * @throws FacturapiException
     */
    public function downloadZipRequest($id): string
    {
        return $this->client->download('invoices/zip-requests/' . $id . '/zip');
    }

    /**
     * Downloads the specified invoice in a PDF file
     *
     * @param string $id Invoice ID.
     * @return string Raw PDF bytes (binary string, not base64-encoded).
     * @throws FacturapiException
     */
    public function downloadPdf($id): string
    {
        return $this->client->download('invoices/' . $id . '/pdf');
    }

    /**
     * @deprecated Use downloadPdf() instead. Will be removed in v5.
     */
    public function download_pdf($id): string
    {
        trigger_error('Invoices::download_pdf() is deprecated and will be removed in v5. Use downloadPdf() instead.', E_USER_DEPRECATED);
        return $this->downloadPdf($id);
    }

    /**
     * Downloads the specified invoice in a XML file
     *
     * @param string $id Invoice ID.
     * @return string XML file contents.
     * @throws FacturapiException
     */
    public function downloadXml($id): string
    {
        return $this->client->download('invoices/' . $id . '/xml');
    }

    /**
     * @deprecated Use downloadXml() instead. Will be removed in v5.
     */
    public function download_xml($id): string
    {
        trigger_error('Invoices::download_xml() is deprecated and will be removed in v5. Use downloadXml() instead.', E_USER_DEPRECATED);
        return $this->downloadXml($id);
    }

    /**
     * Downloads the cancellation receipt of a canceled invoice in XML format
     *
     * @param string $id Invoice ID.
     * @return string XML file contents.
     * @throws FacturapiException
     */
    public function downloadCancellationReceiptXml($id): string
    {
        return $this->client->download('invoices/' . $id . '/cancellation_receipt/xml');
    }

    /**
     * @deprecated Use downloadCancellationReceiptXml() instead. Will be removed in v5.
     */
    public function download_cancellation_receipt_xml($id): string
    {
        trigger_error('Invoices::download_cancellation_receipt_xml() is deprecated and will be removed in v5. Use downloadCancellationReceiptXml() instead.', E_USER_DEPRECATED);
        return $this->downloadCancellationReceiptXml($id);
    }

    /**
     * Downloads the cancellation receipt of a canceled invoice in PDF format
     *
     * @param string $id Invoice ID.
     * @return string PDF file contents.
     *
     * @throws FacturapiException
     */
    public function downloadCancellationReceiptPdf($id): string
    {
        return $this->client->download('invoices/' . $id . '/cancellation_receipt/pdf');
    }

    /**
     * @deprecated Use downloadCancellationReceiptPdf() instead. Will be removed in v5.
     */
    public function download_cancellation_receipt_pdf($id): string
    {
        trigger_error('Invoices::download_cancellation_receipt_pdf() is deprecated and will be removed in v5. Use downloadCancellationReceiptPdf() instead.', E_USER_DEPRECATED);
        return $this->downloadCancellationReceiptPdf($id);
    }

    /**
     * Updates the status of an Invoice with the latest information from the SAT.
     * In Test mode, this method will simulate a status update to a "canceled" status.
     *
     * @param string $id Invoice ID.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function updateStatus($id): mixed
    {
        return $this->client->put('invoices/' . $id . '/status', []);
    }

    /**
     * @deprecated Use updateStatus() instead. Will be removed in v5.
     */
    public function update_status($id): mixed
    {
        trigger_error('Invoices::update_status() is deprecated and will be removed in v5. Use updateStatus() instead.', E_USER_DEPRECATED);
        return $this->updateStatus($id);
    }

    /**
     * Updates an Invoice with "draft" status
     *
     * @param string $id Invoice ID.
     * @param array $body Draft payload.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function updateDraft($id, $body): mixed
    {
        return $this->client->put('invoices/' . $id, $body);
    }

    /**
     * @deprecated Use updateDraft() instead. Will be removed in v5.
     */
    public function update_draft($id, $body): mixed
    {
        trigger_error('Invoices::update_draft() is deprecated and will be removed in v5. Use updateDraft() instead.', E_USER_DEPRECATED);
        return $this->updateDraft($id, $body);
    }

    /**
     * Stamps a draft invoice
     *
     * @param string $id Invoice ID.
     * @param array|null $query URL query parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function stampDraft($id, ?array $query = null): mixed
    {
        return $this->client->post('invoices/' . $id . '/stamp', []);
    }

    /**
     * @deprecated Use stampDraft() instead. Will be removed in v5.
     */
    public function stamp_draft($id, ?array $query = null): mixed
    {
        trigger_error('Invoices::stamp_draft() is deprecated and will be removed in v5. Use stampDraft() instead.', E_USER_DEPRECATED);
        return $this->stampDraft($id, $query);
    }

    /**
     * Creates a new draft Invoice copying the information from the specified Invoice
     *
     * @param string $id Invoice ID.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function copyToDraft($id): mixed
    {
        return $this->client->post('invoices/' . $id . '/copy', []);
    }

    /**
     * @deprecated Use copyToDraft() instead. Will be removed in v5.
     */
    public function copy_to_draft($id): mixed
    {
        trigger_error('Invoices::copy_to_draft() is deprecated and will be removed in v5. Use copyToDraft() instead.', E_USER_DEPRECATED);
        return $this->copyToDraft($id);
    }

    /**
     * Generates a preview of an invoice in PDF format without stamping it or saving it
     *
     * @param array $body Invoice payload.
     * @return string PDF file contents.
     * @throws FacturapiException
     */
    public function previewPdf($body): string
    {
        return $this->client->postRaw('invoices/preview/pdf', $body);
    }

    /**
     * @deprecated Use previewPdf() instead. Will be removed in v5.
     */
    public function preview_pdf($body): string
    {
        trigger_error('Invoices::preview_pdf() is deprecated and will be removed in v5. Use previewPdf() instead.', E_USER_DEPRECATED);
        return $this->previewPdf($body);
    }
}
