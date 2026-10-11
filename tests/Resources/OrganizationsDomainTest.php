<?php

declare(strict_types=1);

namespace Facturapi\Tests\Resources;

use Facturapi\Resources\Organizations;
use Facturapi\Tests\Support\FakeHttpClient;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class OrganizationsDomainTest extends TestCase
{
    public function testAllSerializesQueryParametersIntoUrl(): void
    {
        $httpClient = new FakeHttpClient(new Response(200, [], '{"data":[]}'));
        $organizations = (new \Facturapi\Facturapi('sk_test_abc123', ['httpClient' => $httpClient]))->organizations();

        $organizations->all([
            'q' => 'XAXX010101000',
        ]);

        $request = $httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame(
            'https://www.facturapi.io/v2/organizations?q=XAXX010101000',
            (string) $request->getUri()
        );
    }

    public function testCheckDomainAvailabilityUsesExpectedEndpoint(): void
    {
        $httpClient = new FakeHttpClient(new Response(200, [], '{"available":true}'));
        $organizations = (new \Facturapi\Facturapi('sk_test_abc123', ['httpClient' => $httpClient]))->organizations();

        $result = $organizations->checkDomainAvailability([
            'name' => 'acme',
            'domain' => 'acme.mx',
        ]);

        self::assertTrue($result['available']);

        $request = $httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame(
            'https://www.facturapi.io/v2/organizations/domain-check?name=acme&domain=acme.mx',
            (string) $request->getUri()
        );
    }

    public function testCheckDomainAvailabilityThrowsWhenParamsAreNotArray(): void
    {
        $httpClient = new FakeHttpClient(new Response(200, [], '{"available":true}'));
        $organizations = (new \Facturapi\Facturapi('sk_test_abc123', ['httpClient' => $httpClient]))->organizations();

        $this->expectException(\Facturapi\Exceptions\FacturapiException::class);
        $this->expectExceptionMessage('checkDomainAvailability expects $query to be an array.');

        $organizations->checkDomainAvailability('invalid');
    }
}
