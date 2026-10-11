<?php

declare(strict_types=1);
use Facturapi\Resources\Organizations;
use Facturapi\Tests\Support\FakeHttpClient;
use GuzzleHttp\Psr7\Response;
describe('OrganizationsDomain', function () {
    test('all serializes query parameters into url', function () {
        mockResponse('{"data":[]}', 200);
        $organizations = test()->facturapi->organizations();

        $organizations->all([
            'q' => 'XAXX010101000',
        ]);

        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame(
            'https://www.facturapi.io/v2/organizations?q=XAXX010101000',
            (string) $request->getUri()
        );
    });
    test('check domain availability uses expected endpoint', function () {
        mockResponse('{"available":true}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->checkDomainAvailability([
            'name' => 'acme',
            'domain' => 'acme.mx',
        ]);

        self::assertTrue($result['available']);

        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame(
            'https://www.facturapi.io/v2/organizations/domain-check?name=acme&domain=acme.mx',
            (string) $request->getUri()
        );
    });
    test('check domain availability throws when params are not array', function () {
        mockResponse('{"available":true}', 200);
        $organizations = test()->facturapi->organizations();

        $this->expectException(\Facturapi\Exceptions\FacturapiException::class);
        $this->expectExceptionMessage('checkDomainAvailability expects $query to be an array.');

        $organizations->checkDomainAvailability('invalid');
    });

});
