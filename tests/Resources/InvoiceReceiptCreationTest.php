<?php

declare(strict_types=1);
use Facturapi\Exceptions\FacturapiException;
use Facturapi\Resources\Invoices;
use Facturapi\Resources\Receipts;
use Facturapi\Tests\Support\FakeHttpClient;
use GuzzleHttp\Psr7\Response;
describe('InvoiceReceiptCreation', function () {
    test('invoices create sends json body and query params', function () {
        mockResponse('{"id":"inv_123"}', 201);
        $invoices = test()->facturapi->invoices();

        $payload = [
            'customer' => 'cus_123',
            'items' => [['quantity' => 1]],
        ];

        $result = $invoices->create($payload, ['as' => 'draft', 'validate' => true]);

        self::assertSame('inv_123', $result['id']);

        $request = test()->httpClient->requests()[0];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/invoices?as=draft&validate=true', (string) $request->getUri());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertSame(json_encode($payload), (string) $request->getBody());
    });
    test('receipts create sends json body to expected endpoint', function () {
        mockResponse('{"id":"rec_123"}', 201);
        $receipts = test()->facturapi->receipts();

        $payload = [
            'customer' => 'cus_123',
            'items' => [['quantity' => 1]],
        ];

        $result = $receipts->create($payload);

        self::assertSame('rec_123', $result['id']);

        $request = test()->httpClient->requests()[0];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/receipts', (string) $request->getUri());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertSame(json_encode($payload), (string) $request->getBody());
    });
    test('invoices cancel serializes query params without extra slash', function () {
        mockResponse('{"status":"canceled"}', 200);
        $invoices = test()->facturapi->invoices();

        $invoices->cancel('inv_123', ['motive' => '01', 'substitution' => 'inv_456']);

        $request = test()->httpClient->requests()[0];
        self::assertSame(
            'https://www.facturapi.io/v2/invoices/inv_123?motive=01&substitution=inv_456',
            (string) $request->getUri()
        );
    });
    test('invoices cancel throws on non2xx and preserves error shape', function () {
        $errorBody = [
            'message' => 'Cancellation rejected',
            'code' => 'cancel_error',
            'details' => [
                ['path' => 'motive', 'message' => 'Invalid motive'],
            ],
        ];

        mockResponse(json_encode($errorBody), 409, ['Content-Type' => 'application/json']);
        $invoices = test()->facturapi->invoices();

        try {
            $invoices->cancel('inv_123', ['motive' => '99']);
            self::fail('Expected FacturapiException to be thrown.');
        } catch (FacturapiException $exception) {
            self::assertSame(409, $exception->getStatusCode());
            self::assertSame('Cancellation rejected', $exception->getMessage());
            self::assertSame($errorBody, $exception->getErrorData());
        }
    });

});
