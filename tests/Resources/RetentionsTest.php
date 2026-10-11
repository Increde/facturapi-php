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
        expect($request->getMethod())->toBe('POST');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/retentions');
        expect((string) $request->getBody())->toBeJson()->and(json_decode((string) $request->getBody(), true))->toBe(json_decode('{"status":"draft","customer":null}', true));
    });
    test('update draft uses put on retention id', function () {
        mockResponse('{"id":"ret_123"}', 200);
        $retentions = test()->facturapi->retentions();

        $retentions->updateDraft('ret_123', [
            'folio_int' => 'R-2026-001',
        ]);

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('PUT');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/retentions/ret_123');
        expect((string) $request->getBody())->toBeJson()->and(json_decode((string) $request->getBody(), true))->toBe(json_decode('{"folio_int":"R-2026-001"}', true));
    });
    test('copy to draft uses copy endpoint', function () {
        mockResponse('{"id":"ret_draft"}', 200);
        $retentions = test()->facturapi->retentions();

        $retentions->copyToDraft('ret_123');

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('POST');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/retentions/ret_123/copy');
        expect((string) $request->getBody())->toBe('');
    });
    test('stamp draft uses stamp endpoint', function () {
        mockResponse('{"id":"ret_123","status":"valid"}', 200);
        $retentions = test()->facturapi->retentions();

        $retentions->stampDraft('ret_123');

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('POST');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/retentions/ret_123/stamp');
        expect((string) $request->getBody())->toBe('');
    });
    test('cancel can delete draft without query parameters', function () {
        mockResponse('{"id":"ret_123","deleted":true}', 200);
        $retentions = test()->facturapi->retentions();

        $retentions->cancel('ret_123');

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('DELETE');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/retentions/ret_123');
    });
    test('all can filter by draft status', function () {
        mockResponse('{"data":[]}', 200);
        $retentions = test()->facturapi->retentions();

        $retentions->all([
            'status' => 'draft',
        ]);

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('GET');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/retentions?status=draft');
    });

});
