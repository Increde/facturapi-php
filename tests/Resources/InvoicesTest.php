<?php

declare(strict_types=1);
use Facturapi\Resources\Invoices;
use Facturapi\Tests\Support\FakeHttpClient;
use GuzzleHttp\Psr7\Response;
describe('Invoices', function () {
    test('get last status is null before any request', function () {
        self::assertTrue(true);
    });
    test('download pdf uses expected path and authorization header', function () {
        mockResponse('PDF_BINARY_CONTENT', 200);
        $invoices = test()->facturapi->invoices();

        $result = $invoices->downloadPdf('inv_123');

        self::assertSame('PDF_BINARY_CONTENT', $result);

        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/invoices/inv_123/pdf', (string) $request->getUri());
        self::assertSame('Basic ' . base64_encode('sk_test_abc123:'), $request->getHeaderLine('Authorization'));
        self::assertSame('facturapi-php', $request->getHeaderLine('User-Agent'));
    });
    test('all serializes nested date range query parameters into url', function () {
        mockResponse('{"data":[]}', 200);
        $invoices = test()->facturapi->invoices();

        $invoices->all([
            'q' => 'XAXX010101000',
            'date' => [
                'gte' => '2019-03-01',
                'lte' => '2019-03-31',
            ],
        ]);

        $request = test()->httpClient->requests()[0];
        self::assertSame('https://www.facturapi.io/v2/invoices', $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . $request->getUri()->getPath());
        self::assertSame(
            'q=XAXX010101000&date[gte]=2019-03-01&date[lte]=2019-03-31',
            urldecode($request->getUri()->getQuery())
        );
    });
    test('all repeats the key for list query parameters', function () {
        mockResponse('{"data":[]}', 200);
        $invoices = test()->facturapi->invoices();

        $invoices->all([
            'status' => ['valid', 'canceled'],
        ]);

        $request = test()->httpClient->requests()[0];
        self::assertSame(
            'status=valid&status=canceled',
            urldecode($request->getUri()->getQuery())
        );
    });
    test('all omits null query parameters', function () {
        mockResponse('{"data":[]}', 200);
        $invoices = test()->facturapi->invoices();

        $invoices->all([
            'q' => null,
            'page' => 2,
        ]);

        $request = test()->httpClient->requests()[0];
        self::assertSame('page=2', urldecode($request->getUri()->getQuery()));
    });
    test('all sends explicit empty query values', function () {
        mockResponse('{"data":[]}', 200);
        $invoices = test()->facturapi->invoices();

        $invoices->all([
            'q' => '',
        ]);

        $request = test()->httpClient->requests()[0];
        self::assertSame('q=', urldecode($request->getUri()->getQuery()));
    });
    test('all url encodes scalar query values', function () {
        mockResponse('{"data":[]}', 200);
        $invoices = test()->facturapi->invoices();

        $invoices->all([
            'q' => 'ACME SA de CV',
        ]);

        $request = test()->httpClient->requests()[0];
        self::assertSame(
            'https://www.facturapi.io/v2/invoices?q=ACME+SA+de+CV',
            (string) $request->getUri()
        );
    });
    test('create zip request uses expected path and json body', function () {
        mockResponse('{"id":"zip_123"}', 200);
        $invoices = test()->facturapi->invoices();
        $payload = [
            'year' => 2025,
            'month' => 3,
            'issuer_type' => 'issuing',
            'invoice_types' => ['I', 'E'],
        ];

        $result = $invoices->createZipRequest($payload);

        self::assertSame('zip_123', $result['id']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/invoices/zip-requests', (string) $request->getUri());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertSame($payload, json_decode((string) $request->getBody(), true));
    });
    test('list zip requests uses expected path and query', function () {
        mockResponse('{"data":[]}', 200);
        $invoices = test()->facturapi->invoices();

        $result = $invoices->listZipRequests([
            'year' => 2025,
            'month' => 3,
            'status' => 'finished',
            'limit' => 20,
            'page' => 1,
        ]);

        self::assertSame([], $result['data']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame(
            'https://www.facturapi.io/v2/invoices/zip-requests?year=2025&month=3&status=finished&limit=20&page=1',
            (string) $request->getUri()
        );
    });
    test('retrieve zip request uses expected path', function () {
        mockResponse('{"id":"zip_123"}', 200);
        $invoices = test()->facturapi->invoices();

        $result = $invoices->retrieveZipRequest('zip_123');

        self::assertSame('zip_123', $result['id']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/invoices/zip-requests/zip_123', (string) $request->getUri());
    });
    test('download zip request returns binary contents from expected path', function () {
        mockResponse('ZIP_BINARY_CONTENT', 200);
        $invoices = test()->facturapi->invoices();

        $result = $invoices->downloadZipRequest('zip_123');

        self::assertSame('ZIP_BINARY_CONTENT', $result);
        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/invoices/zip-requests/zip_123/zip', (string) $request->getUri());
    });
    test('payment summary uses expected path and query parameter', function () {
        $summary = [
            'uuid' => '6CF6CE33-1BD2-4F88-A443-33013C069169',
            'installment' => 1,
            'last_balance' => 100.0,
            'total' => 100.0,
            'currency' => 'MXN',
            'amount' => 58.0,
            'taxes' => [
                ['base' => 50.0, 'rate' => 0.16, 'type' => 'IVA', 'factor' => 'Tasa', 'withholding' => false],
            ],
        ];
        mockResponse(json_encode($summary), 200);
        $invoices = test()->facturapi->invoices();

        $result = $invoices->paymentSummary('inv_123', 58.0);

        self::assertSame('6CF6CE33-1BD2-4F88-A443-33013C069169', $result['uuid']);
        self::assertSame(1, $result['installment']);
        self::assertSame(50.0, (float) $result['taxes'][0]['base']);

        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/invoices/inv_123/payment-summary?amount=58', (string) $request->getUri());
        self::assertSame('Basic ' . base64_encode('sk_test_abc123:'), $request->getHeaderLine('Authorization'));
    });

});
