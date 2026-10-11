<?php

declare(strict_types=1);


describe('Organizations', function () {
    test('all retrieves list of organizations', function () {
        mockResponse('{"data":[]}', 200);
        $organizations = test()->facturapi->organizations();

        $organizations->all();

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('GET');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/organizations');
    });

    test('retrieve fetches a single organization by id', function () {
        mockResponse('{"id":"org_123"}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->retrieve('org_123');

        expect($result['id'])->toBe('org_123');
        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('GET');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/organizations/org_123');
    });

    test('create sends json body with name', function () {
        mockResponse('{"id":"org_123"}', 201);
        $organizations = test()->facturapi->organizations();

        $payload = ['name' => 'Mi Empresa'];
        $result = $organizations->create($payload);

        expect($result['id'])->toBe('org_123');
        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('POST');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/organizations');
        expect(json_decode((string) $request->getBody(), true))->toBe($payload);
    });

    test('updateLegal updates legal info', function () {
        mockResponse('{"id":"org_123"}', 200);
        $organizations = test()->facturapi->organizations();

        $payload = ['legal_name' => 'Empresa SA', 'tax_id' => 'XAXX010101000'];
        $result = $organizations->updateLegal('org_123', $payload);

        expect($result['id'])->toBe('org_123');
        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('PUT');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/organizations/org_123/legal');
        expect(json_decode((string) $request->getBody(), true))->toBe($payload);
    });

    test('updateCustomization updates colors', function () {
        mockResponse('{"id":"org_123"}', 200);
        $organizations = test()->facturapi->organizations();

        $payload = ['color' => '#FFFFFF'];
        $result = $organizations->updateCustomization('org_123', $payload);

        expect($result['id'])->toBe('org_123');
        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('PUT');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/organizations/org_123/customization');
        expect(json_decode((string) $request->getBody(), true))->toBe($payload);
    });

    test('updateReceiptSettings updates settings', function () {
        mockResponse('{"id":"org_123"}', 200);
        $organizations = test()->facturapi->organizations();

        $payload = ['next_folio' => 100];
        $result = $organizations->updateReceiptSettings('org_123', $payload);

        expect($result['id'])->toBe('org_123');
        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('PUT');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/organizations/org_123/receipts');
        expect(json_decode((string) $request->getBody(), true))->toBe($payload);
    });

    test('updateDomain updates domain', function () {
        mockResponse('{"id":"org_123"}', 200);
        $organizations = test()->facturapi->organizations();

        $payload = ['domain' => 'my-domain'];
        $result = $organizations->updateDomain('org_123', $payload);

        expect($result['id'])->toBe('org_123');
        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('PUT');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/organizations/org_123/domain');
        expect(json_decode((string) $request->getBody(), true))->toBe($payload);
    });

    test('updateSelfInvoiceSettings updates self invoice settings', function () {
        mockResponse('{"id":"org_123"}', 200);
        $organizations = test()->facturapi->organizations();

        $payload = ['active' => true];
        $result = $organizations->updateSelfInvoiceSettings('org_123', $payload);

        expect($result['id'])->toBe('org_123');
        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('PUT');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/organizations/org_123/self-invoice');
        expect(json_decode((string) $request->getBody(), true))->toBe($payload);
    });

    test('getTestApiKey retrieves test key', function () {
        mockResponse('{"id":"org_123"}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->getTestApiKey('org_123');

        expect($result['id'])->toBe('org_123');
        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('GET');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/organizations/org_123/apikeys/test');
    });

    test('renewTestApiKey renews test key', function () {
        mockResponse('{"id":"org_123"}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->renewTestApiKey('org_123');

        expect($result['id'])->toBe('org_123');
        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('PUT');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/organizations/org_123/apikeys/test');
    });

    test('listLiveApiKeys retrieves live keys', function () {
        mockResponse('{"data":[]}', 200);
        $organizations = test()->facturapi->organizations();

        $organizations->listLiveApiKeys('org_123');

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('GET');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/organizations/org_123/apikeys/live');
    });
});
