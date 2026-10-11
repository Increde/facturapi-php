<?php

declare(strict_types=1);
use Facturapi\Resources\Organizations;
use Facturapi\Tests\Support\FakeHttpClient;
use GuzzleHttp\Psr7\Response;
describe('OrganizationsTeamManagement', function () {
    test('list team access uses expected endpoint', function () {
        mockResponse('[]', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->listTeamAccess('org_123');

        self::assertIsArray($result);
        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team', (string) $request->getUri());
    });
    test('retrieve team access uses expected endpoint', function () {
        mockResponse('{"id":"acc_123"}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->retrieveTeamAccess('org_123', 'acc_123');

        self::assertSame('acc_123', $result['id']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/acc_123', (string) $request->getUri());
    });
    test('invite user to team uses expected endpoint and json body', function () {
        mockResponse('{"id":"inv_123"}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->inviteUserToTeam('org_123', [
            'email' => 'alex@example.com',
            'role' => 'role_123',
        ]);

        self::assertSame('inv_123', $result['id']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/invites', (string) $request->getUri());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertStringContainsString('"email":"alex@example.com"', (string) $request->getBody());
        self::assertStringContainsString('"role":"role_123"', (string) $request->getBody());
    });
    test('list sent team invites uses expected endpoint', function () {
        mockResponse('[]', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->listSentTeamInvites('org_123');

        self::assertIsArray($result);
        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/invites', (string) $request->getUri());
    });
    test('cancel team invite uses expected endpoint', function () {
        mockResponse('{"ok":true}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->cancelTeamInvite('org_123', 'inv_123');

        self::assertTrue($result['ok']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('DELETE', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/invites/inv_123', (string) $request->getUri());
    });
    test('list received team invites uses expected endpoint', function () {
        mockResponse('[]', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->listReceivedTeamInvites();

        self::assertIsArray($result);
        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/invites/pending', (string) $request->getUri());
    });
    test('respond team invite uses expected endpoint and json body', function () {
        mockResponse('{"ok":true}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->respondTeamInvite('inv_123', ['accept' => true]);

        self::assertTrue($result['ok']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/invites/inv_123/response', (string) $request->getUri());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertStringContainsString('"accept":true', (string) $request->getBody());
    });
    test('list team roles uses expected endpoint', function () {
        mockResponse('[]', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->listTeamRoles('org_123');

        self::assertIsArray($result);
        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/roles', (string) $request->getUri());
    });
    test('list team role templates uses expected endpoint', function () {
        mockResponse('[]', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->listTeamRoleTemplates('org_123');

        self::assertIsArray($result);
        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/roles/templates', (string) $request->getUri());
    });
    test('list team role operations uses expected endpoint', function () {
        mockResponse('[]', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->listTeamRoleOperations('org_123');

        self::assertIsArray($result);
        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/roles/operations', (string) $request->getUri());
    });
    test('retrieve team role uses expected endpoint', function () {
        mockResponse('{"id":"role_123"}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->retrieveTeamRole('org_123', 'role_123');

        self::assertSame('role_123', $result['id']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/roles/role_123', (string) $request->getUri());
    });
    test('create team role uses expected endpoint and json body', function () {
        mockResponse('{"id":"role_123","name":"Billing analyst"}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->createTeamRole('org_123', [
            'name' => 'Billing analyst',
            'operations' => ['read:invoices'],
        ]);

        self::assertSame('role_123', $result['id']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/roles', (string) $request->getUri());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertStringContainsString('"name":"Billing analyst"', (string) $request->getBody());
        self::assertStringContainsString('"operations":["read:invoices"]', (string) $request->getBody());
    });
    test('update team access role uses expected endpoint and json body', function () {
        mockResponse('{"id":"acc_123","role":"role_123"}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->updateTeamAccessRole('org_123', 'acc_123', 'role_123');

        self::assertSame('acc_123', $result['id']);
        self::assertSame('role_123', $result['role']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('PUT', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/acc_123/role', (string) $request->getUri());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertStringContainsString('"role":"role_123"', (string) $request->getBody());
    });
    test('remove team access uses expected endpoint', function () {
        mockResponse('{"ok":true}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->removeTeamAccess('org_123', 'acc_123');

        self::assertTrue($result['ok']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('DELETE', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/acc_123', (string) $request->getUri());
    });
    test('update team role uses expected endpoint and json body', function () {
        mockResponse('{"id":"role_123","name":"Senior billing analyst"}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->updateTeamRole('org_123', 'role_123', [
            'name' => 'Senior billing analyst',
        ]);

        self::assertSame('role_123', $result['id']);
        self::assertSame('Senior billing analyst', $result['name']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('PUT', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/roles/role_123', (string) $request->getUri());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertStringContainsString('"name":"Senior billing analyst"', (string) $request->getBody());
    });
    test('delete team role uses expected endpoint', function () {
        mockResponse('{"ok":true}', 200);
        $organizations = test()->facturapi->organizations();

        $result = $organizations->deleteTeamRole('org_123', 'role_123');

        self::assertTrue($result['ok']);
        $request = test()->httpClient->requests()[0];
        self::assertSame('DELETE', $request->getMethod());
        self::assertSame('https://www.facturapi.io/v2/organizations/org_123/team/roles/role_123', (string) $request->getUri());
    });

});
