<?php

declare(strict_types=1);
use Facturapi\Resources\Retentions;
use Facturapi\Tests\Support\FakeHttpClient;
use GuzzleHttp\Psr7\Response;
describe('Retentions', function () {
    test('create can send draft retention payload', function () {
        mockResponse('{"id":"ret_draft","status":"draft"}', 200);
        $retentions = test()->facturapi->retentions();

        $retentions->create([
            'status' => 'draft',
            'customer' => null,
        ]);

        $request = test()->httpClient->requests()[0];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/retentions', (string) $request->getUri());
        self::assertJsonStringEqualsJsonString(
            '{"status":"draft","customer":null}',
            (string) $request->getBody()
        );
    });
    test('update draft uses put on retention id', function () {
        mockResponse('{"id":"ret_123"}', 200);
        $retentions = test()->facturapi->retentions();

        $retentions->updateDraft('ret_123', [
            'folio_int' => 'R-2026-001',
        ]);

        $request = test()->httpClient->requests()[0];
        self::assertSame('PUT', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/retentions/ret_123', (string) $request->getUri());
        self::assertJsonStringEqualsJsonString(
            '{"folio_int":"R-2026-001"}',
            (string) $request->getBody()
        );
    });
    test('copy to draft uses copy endpoint', function () {
        mockResponse('{"id":"ret_draft"}', 200);
        $retentions = test()->facturapi->retentions();

        $retentions->copyToDraft('ret_123');

        $request = test()->httpClient->requests()[0];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/retentions/ret_123/copy', (string) $request->getUri());
        self::assertSame('', (string) $request->getBody());
    });
    test('stamp draft uses stamp endpoint', function () {
        mockResponse('{"id":"ret_123","status":"valid"}', 200);
        $retentions = test()->facturapi->retentions();

        $retentions->stampDraft('ret_123');

        $request = test()->httpClient->requests()[0];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/retentions/ret_123/stamp', (string) $request->getUri());
        self::assertSame('', (string) $request->getBody());
    });
    test('cancel can delete draft without query parameters', function () {
        mockResponse('{"id":"ret_123","deleted":true}', 200);
        $retentions = test()->facturapi->retentions();

        $retentions->cancel('ret_123');

        $request = test()->httpClient->requests()[0];
        self::assertSame('DELETE', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/retentions/ret_123', (string) $request->getUri());
    });
    test('all can filter by draft status', function () {
        mockResponse('{"data":[]}', 200);
        $retentions = test()->facturapi->retentions();

        $retentions->all([
            'status' => 'draft',
        ]);

        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/retentions?status=draft', (string) $request->getUri());
    });

});
