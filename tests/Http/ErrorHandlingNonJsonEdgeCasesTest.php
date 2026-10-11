<?php

declare(strict_types=1);
use Facturapi\Exceptions\FacturapiException;
use Facturapi\Resources\Invoices;
use Facturapi\Tests\Support\FakeHttpClient;
use GuzzleHttp\Psr7\Response;
describe('ErrorHandlingNonJsonEdgeCases', function () {
    test('invalid json error body preserves raw body', function () {
        $rawBody = '{"message": "broken"';

        mockResponse($rawBody, 500, ['Content-Type' => 'application/json']);

        $invoices = test()->facturapi->invoices();

        try {
            $invoices->create(['customer' => []]);
            self::fail('Expected FacturapiException to be thrown.');
        } catch (FacturapiException $exception) {
            self::assertSame(500, $exception->getStatusCode());
            self::assertSame($rawBody, $exception->getMessage());
            self::assertNull($exception->getErrorData());
            self::assertSame($rawBody, $exception->getRawBody());
        }
    });
    test('empty error body is exposed as empty string', function () {
        mockResponse('', 500, ['Content-Type' => 'text/plain']);

        $invoices = test()->facturapi->invoices();

        try {
            $invoices->create(['customer' => []]);
            self::fail('Expected FacturapiException to be thrown.');
        } catch (FacturapiException $exception) {
            self::assertSame(500, $exception->getStatusCode());
            self::assertSame('', $exception->getMessage());
            self::assertNull($exception->getErrorData());
            self::assertSame('', $exception->getRawBody());
        }
    });
    test('plain text error body is preserved', function () {
        $rawBody = 'gateway overloaded, retry later';

        mockResponse($rawBody, 503, ['Content-Type' => 'text/plain']);

        $invoices = test()->facturapi->invoices();

        try {
            $invoices->create(['customer' => []]);
            self::fail('Expected FacturapiException to be thrown.');
        } catch (FacturapiException $exception) {
            self::assertSame(503, $exception->getStatusCode());
            self::assertSame($rawBody, $exception->getMessage());
            self::assertNull($exception->getErrorData());
            self::assertSame($rawBody, $exception->getRawBody());
        }
    });

});
