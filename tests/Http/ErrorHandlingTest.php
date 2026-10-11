<?php

declare(strict_types=1);
use Facturapi\Exceptions\FacturapiException;
use Facturapi\Resources\Invoices;
use Facturapi\Tests\Support\FakeHttpClient;
use GuzzleHttp\Psr7\Response;
describe('ErrorHandling', function () {
    test('api error shape is fully available on exception', function () {
        $errorBody = [
            'message' => 'Request validation failed',
            'code' => 'validation_error',
            'path' => 'customer.tax_id',
            'location' => 'body',
            'details' => [
                [
                    'path' => 'customer.tax_id',
                    'message' => 'customer.tax_id must be a valid RFC',
                    'code' => 'invalid_rfc',
                ],
            ],
            'errors' => [
                [
                    'path' => 'customer.tax_id',
                    'location' => 'body',
                    'message' => 'customer.tax_id must be a valid RFC',
                    'code' => 'invalid_rfc',
                ],
            ],
        ];

        mockResponse(json_encode($errorBody), 422, [
                'Content-Type' => 'application/json',
                'Retry-After' => '3',
                'x-facturapi-log-id' => 'log_123',
            ]);

        $invoices = test()->facturapi->invoices();

        try {
            $invoices->create(['customer' => []]);
            self::fail('Expected FacturapiException to be thrown.');
        } catch (FacturapiException $exception) {
            self::assertSame(422, $exception->getStatusCode());
            self::assertSame('Request validation failed', $exception->getMessage());
            self::assertSame($errorBody, $exception->getErrorData());
            self::assertSame($errorBody, $exception->getResponseData());
            self::assertSame(json_encode($errorBody), $exception->getRawBody());

            self::assertSame('validation_error', $exception->getErrorData()['code']);
            self::assertSame('validation_error', $exception->getErrorCode());
            self::assertSame('customer.tax_id', $exception->getErrorPath());
            self::assertSame('body', $exception->getErrorLocation());
            self::assertSame($errorBody['errors'], $exception->getErrors());
            self::assertSame('log_123', $exception->getLogId());
            self::assertSame('3', $exception->getResponseHeaders()['retry-after']);
            self::assertSame('log_123', $exception->getResponseHeaders()['x-facturapi-log-id']);
            self::assertSame('customer.tax_id', $exception->getErrorData()['details'][0]['path']);
            self::assertSame('invalid_rfc', $exception->getErrorData()['details'][0]['code']);
        }
    });
    test('non json errors still expose raw body', function () {
        $rawBody = '<html><body>502 Bad Gateway</body></html>';

        mockResponse($rawBody, 502, ['Content-Type' => 'text/html']);

        $invoices = test()->facturapi->invoices();

        try {
            $invoices->create(['customer' => []]);
            self::fail('Expected FacturapiException to be thrown.');
        } catch (FacturapiException $exception) {
            self::assertSame(502, $exception->getStatusCode());
            self::assertSame($rawBody, $exception->getMessage());
            self::assertNull($exception->getErrorData());
            self::assertSame($rawBody, $exception->getRawBody());
        }
    });
    test('numeric api error codes are converted to strings', function () {
        mockResponse('{"code": 400}', 400, ['Content-Type' => 'application/json']);

        $invoices = test()->facturapi->invoices();

        try {
            $invoices->create(['customer' => []]);
            self::fail('Expected FacturapiException to be thrown.');
        } catch (FacturapiException $exception) {
            self::assertSame('400', $exception->getErrorCode());
        }
    });

});
