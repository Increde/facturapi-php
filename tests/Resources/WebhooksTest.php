<?php

declare(strict_types=1);
use Facturapi\Resources\Webhooks;
use Facturapi\Tests\Support\FakeHttpClient;
use GuzzleHttp\Psr7\Response;
describe('Webhooks', function () {
    test('validate signature uses local verification by default', function () {
        
        $webhooks = test()->facturapi->webhooks();

        $rawPayload = '{"id":"evt_123"}';
        $secret = 'whsec_test_secret';
        $hex = hash_hmac('sha256', $rawPayload, $secret);

        $result = $webhooks->validateSignature([
            'body' => $rawPayload,
            'signature' => 'sha256=' . $hex,
            'webhookSecret' => $secret,
        ]);

        self::assertTrue($result['valid']);
        self::assertCount(0, test()->httpClient->requests());
    });
    test('validate signature falls back to api endpoint when local cannot run', function () {
        mockResponse('{"valid":true}', 200);
        $webhooks = test()->facturapi->webhooks();

        $payload = [
            'body' => '{"id":"evt_123"}',
            'signature' => 'sha256=fake_signature',
            // No webhookSecret -> triggers API fallback
        ];

        $result = $webhooks->validateSignature($payload);

        self::assertTrue($result['valid']);

        $request = test()->httpClient->requests()[0];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/webhooks/validate-signature', (string) $request->getUri());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertSame(json_encode($payload), (string) $request->getBody());
    });
    test('validate signature local supports raw hex and rejects invalid signatures', function () {
        
        $webhooks = test()->facturapi->webhooks();

        $rawPayload = '{"id":"evt_456"}';
        $secret = 'whsec_test_secret';
        $hex = hash_hmac('sha256', $rawPayload, $secret);

        $valid = $webhooks->validateSignature([
            'payload' => $rawPayload,
            'signature' => $hex,
            'secret' => $secret,
        ]);
        $invalid = $webhooks->validateSignature([
            'payload' => $rawPayload,
            'signature' => 'sha256=invalid',
            'secret' => $secret,
        ]);

        self::assertTrue($valid['valid']);
        self::assertFalse($invalid['valid']);
        self::assertCount(0, test()->httpClient->requests());
    });

});
