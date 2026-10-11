<?php

declare(strict_types=1);

use Facturapi\Tests\Support\FakeHttpClient;

describe('Customers', function () {
    test('create sends full json body with all required and optional properties', function () {
        mockResponse('{"id":"cus_123"}', 201);
        $customers = test()->facturapi->customers();

        $payload = [
            'legal_name' => 'ACME SA DE CV',
            'tax_id' => 'XAXX010101000',
            'tax_system' => '601',
            'address' => [
                'zip' => '01000',
                'street' => 'Av. de los Insurgentes Sur',
                'exterior' => '123',
                'interior' => 'Piso 4',
                'neighborhood' => 'Roma Norte',
                'city' => 'Ciudad de México',
                'municipality' => 'Cuauhtémoc',
                'state' => 'DIF',
                'country' => 'MEX'
            ],
            'email' => 'contact@acme.com',
            'phone' => '5555555555'
        ];

        $result = $customers->create($payload, ['createEditLink' => 1]);

        expect($result->id)->toBe('cus_123');

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('POST');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/customers?createEditLink=1');
        expect($request->getHeaderLine('Content-Type'))->toBe('application/json');
        
        $sentPayload = json_decode((string) $request->getBody(), true);
        expect($sentPayload)->toBe($payload);
    });

    test('update sends json body with all properties', function () {
        mockResponse('{"id":"cus_123"}', 200);
        $customers = test()->facturapi->customers();

        $payload = [
            'legal_name' => 'ACME Modificado SA DE CV',
            'email' => 'new@acme.com',
        ];

        $result = $customers->update('cus_123', $payload, ['createEditLink' => 0]);

        expect($result->id)->toBe('cus_123');

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('PUT');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/customers/cus_123?createEditLink=0');
        expect($request->getHeaderLine('Content-Type'))->toBe('application/json');
        
        $sentPayload = json_decode((string) $request->getBody(), true);
        expect($sentPayload)->toBe($payload);
    });

    test('retrieve fetches a single customer by id', function () {
        mockResponse('{"id":"cus_123"}', 200);
        $customers = test()->facturapi->customers();

        $result = $customers->retrieve('cus_123');

        expect($result->id)->toBe('cus_123');

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('GET');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/customers/cus_123');
    });

    test('delete removes a customer by id', function () {
        mockResponse('{"id":"cus_123"}', 200);
        $customers = test()->facturapi->customers();

        $result = $customers->delete('cus_123');

        expect($result->id)->toBe('cus_123');

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('DELETE');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/customers/cus_123');
    });

    test('all serializes query parameters correctly', function () {
        mockResponse('{"data":[]}', 200);
        $customers = test()->facturapi->customers();

        $customers->all([
            'q' => 'acme',
            'date' => [
                'gte' => '2023-01-01',
            ],
            'page' => 1,
            'limit' => 50
        ]);

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('GET');
        expect(urldecode($request->getUri()->getQuery()))->toBe('q=acme&date[gte]=2023-01-01&page=1&limit=50');
    });

    test('validate tax info sends correct payload', function () {
        mockResponse('{"is_valid":true}', 200);
        $customers = test()->facturapi->customers();

        $result = $customers->validateTaxInfo('cus_123');

        expect($result->is_valid)->toBeTrue();

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('GET');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/customers/cus_123/tax-info-validation');
    });

    test('send edit link by email sends correct payload', function () {
        mockResponse('{"success":true}', 200);
        $customers = test()->facturapi->customers();

        $payload = ['email' => 'edit@acme.com'];
        $result = $customers->sendEditLink('cus_123', $payload);

        expect($result['success'])->toBeTrue();

        $request = test()->httpClient->requests()[0];
        expect($request->getMethod())->toBe('POST');
        expect((string) $request->getUri())->toBe('https://www.facturapi.io/v2/customers/cus_123/email-edit-link');
        
        $sentPayload = json_decode((string) $request->getBody(), true);
        expect($sentPayload)->toBe($payload);
    });
});
