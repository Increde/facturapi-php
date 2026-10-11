<?php

declare(strict_types=1);
use Facturapi\Exceptions\FacturapiException;
use Facturapi\Resources\Organizations;
use Facturapi\Tests\Support\FakeHttpClient;
use GuzzleHttp\Psr7\Response;
describe('OrganizationsMultipart', function () {
    test('upload logo builds multipart request', function () {
        $tmpLogo = tempnam(sys_get_temp_dir(), 'logo_');
        file_put_contents($tmpLogo, 'LOGO_BYTES');

        try {
            mockResponse('{"ok":true}', 200);
            $organizations = test()->facturapi->organizations();

            $result = $organizations->uploadLogo('org_123', $tmpLogo);

            self::assertTrue($result['ok']);

            $request = test()->httpClient->requests()[0];
            self::assertSame('PUT', $request->getMethod());
            self::assertSame('https://www.facturapi.io/v2/organizations/org_123/logo', (string) $request->getUri());
            self::assertStringStartsWith('multipart/form-data; boundary=', $request->getHeaderLine('Content-Type'));

            $body = (string) $request->getBody();
            self::assertStringContainsString('name="file"', $body);
            self::assertStringContainsString('filename="' . basename($tmpLogo) . '"', $body);
            self::assertStringContainsString('LOGO_BYTES', $body);
        } finally {
            @unlink($tmpLogo);
        }
    });
    test('upload certificate builds multipart request with cer key and password', function () {
        $tmpCer = tempnam(sys_get_temp_dir(), 'cer_');
        $tmpKey = tempnam(sys_get_temp_dir(), 'key_');
        file_put_contents($tmpCer, 'CER_BYTES');
        file_put_contents($tmpKey, 'KEY_BYTES');

        try {
            mockResponse('{"ok":true}', 200);
            $organizations = test()->facturapi->organizations();

            $result = $organizations->uploadCertificate('org_123', [
                'cerFile' => $tmpCer,
                'keyFile' => $tmpKey,
                'password' => 'secret_password',
            ]);

            self::assertTrue($result['ok']);

            $request = test()->httpClient->requests()[0];
            self::assertSame('PUT', $request->getMethod());
            self::assertSame('https://www.facturapi.io/v2/organizations/org_123/certificate', (string) $request->getUri());

            $body = (string) $request->getBody();
            self::assertStringContainsString('name="cer"', $body);
            self::assertStringContainsString('name="key"', $body);
            self::assertStringContainsString('name="password"', $body);
            self::assertStringContainsString('CER_BYTES', $body);
            self::assertStringContainsString('KEY_BYTES', $body);
            self::assertStringContainsString('secret_password', $body);
        } finally {
            @unlink($tmpCer);
            @unlink($tmpKey);
        }
    });
    test('upload certificate fails when required fields are missing', function () {
        mockResponse('{"ok":true}', 200);
        $organizations = test()->facturapi->organizations();

        $this->expectException(FacturapiException::class);
        $this->expectExceptionMessage('Invalid certificate payload. Expected cerFile, keyFile and password.');

        $organizations->uploadCertificate('org_123', [
            'cerFile' => '/tmp/cert.cer',
        ]);
    });
    test('upload logo fails when file cannot be opened', function () {
        mockResponse('{"ok":true}', 200);
        $organizations = test()->facturapi->organizations();

        $this->expectException(FacturapiException::class);
        $this->expectExceptionMessage('Unable to open file: /tmp/file_that_does_not_exist.pdf');

        $organizations->uploadLogo('org_123', '/tmp/file_that_does_not_exist.pdf');
    });
    test('upload logo throws on non2xx and preserves error shape', function () {
        $tmpLogo = tempnam(sys_get_temp_dir(), 'logo_');
        file_put_contents($tmpLogo, 'LOGO_BYTES');

        $errorBody = [
            'message' => 'Upload failed',
            'code' => 'upload_error',
            'details' => [
                ['path' => 'file', 'message' => 'Invalid file'],
            ],
        ];

        mockResponse(json_encode($errorBody), 422, ['Content-Type' => 'application/json']);
        $organizations = test()->facturapi->organizations();

        try {
            $organizations->uploadLogo('org_123', $tmpLogo);
            self::fail('Expected FacturapiException to be thrown.');
        } catch (FacturapiException $exception) {
            self::assertSame(422, $exception->getStatusCode());
            self::assertSame('Upload failed', $exception->getMessage());
            self::assertSame($errorBody, $exception->getErrorData());
        } finally {
            @unlink($tmpLogo);
        }
    });

});
