<?php

namespace Tests\AppBundle\Controller\v1;

use AppBundle\Entity\User;

class SecurityTest extends ApiTestCase
{
    private function checkRequest(string $token, array $results) {
        foreach ($results as $path => $data) {
            $request = $this->client->request(
                $data['method'],
                $path,
                ['headers' => ['Authorization' => $token]]
            );
            $this->assertEquals($data['code'], $request->getStatusCode());
        }
    }

    private function createUser(array $data) {
        $user = new User($data['username'], $data['token']);

        $user->setRoles($data['roles']);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function testRootRole()
    {
        $userData = [
            'username' => 'test_1',
            'token' => 'a2540dc6-5b0b-45b9-8a7d-8c6fcf03e001',
            'roles' => ['ROLE_ROOT']
        ];
        $results = [
            'patches/location/from_reporter' => ['code' => 400, 'method' => 'POST'],
            'patches/location' => ['code' => 400, 'method' => 'POST'],
            'patches/status' => ['code' => 400, 'method' => 'POST'],
            'elevators/import/csv' => ['code' => 400, 'method' => 'POST'],
            'status' => ['code' => 200, 'method' => 'GET']
        ];

        $this->createUser($userData);
        $this->checkRequest($userData['token'], $results);
    }

    public function testCreateEquipmentStatusRole()
    {
        $userData = [
            'username' => 'test_2',
            'token' => 'a2540dc6-5b0b-45b9-8a7d-8c6fcf03e002',
            'roles' => ['ROLE_V1_STATUS_PATCH_CREATE_EQUIPMENT_STATUS']
        ];
        $results = [
            'patches/location/from_reporter' => ['code' => 403, 'method' => 'POST'],
            'patches/location' => ['code' => 403, 'method' => 'POST'],
            'patches/status' => ['code' => 400, 'method' => 'POST'],
            'elevators/import/csv' => ['code' => 403, 'method' => 'POST'],
            'status' => ['code' => 403, 'method' => 'GET']
        ];

        $this->createUser($userData);
        $this->checkRequest($userData['token'], $results);
    }

    public function testCreateLocationPatch()
    {
        $userData = [
            'username' => 'test_3',
            'token' => 'a2540dc6-5b0b-45b9-8a7d-8c6fcf03e003',
            'roles' => ['ROLE_V1_LOCATION_PATCH_CREATE']
        ];
        $results = [
            'patches/location/from_reporter' => ['code' => 403, 'method' => 'POST'],
            'patches/location' => ['code' => 400, 'method' => 'POST'],
            'patches/status' => ['code' => 403, 'method' => 'POST'],
            'elevators/import/csv' => ['code' => 403, 'method' => 'POST'],
            'status' => ['code' => 403, 'method' => 'GET']
        ];

        $this->createUser($userData);
        $this->checkRequest($userData['token'], $results);
    }

    public function testCreateLocationPatchFromReporter()
    {
        $userData = [
            'username' => 'test_4',
            'token' => 'a2540dc6-5b0b-45b9-8a7d-8c6fcf03e004',
            'roles' => ['ROLE_V1_LOCATION_PATCH_CREATE_FROM_REPORTER']
        ];
        $results = [
            'patches/location/from_reporter' => ['code' => 400, 'method' => 'POST'],
            'patches/location' => ['code' => 403, 'method' => 'POST'],
            'patches/status' => ['code' => 403, 'method' => 'POST'],
            'elevators/import/csv' => ['code' => 403, 'method' => 'POST'],
            'status' => ['code' => 403, 'method' => 'GET']
        ];

        $this->createUser($userData);
        $this->checkRequest($userData['token'], $results);
    }

    public function testElevatorImportCsv()
    {
        $userData = [
            'username' => 'test_5',
            'token' => 'a2540dc6-5b0b-45b9-8a7d-8c6fcf03e005',
            'roles' => ['ROLE_V1_ELEVATOR_IMPORT_CSV']
        ];
        $results = [
            'patches/location/from_reporter' => ['code' => 403, 'method' => 'POST'],
            'patches/location' => ['code' => 403, 'method' => 'POST'],
            'patches/status' => ['code' => 403, 'method' => 'POST'],
            'elevators/import/csv' => ['code' => 400, 'method' => 'POST'],
            'status' => ['code' => 403, 'method' => 'GET']
        ];

        $this->createUser($userData);
        $this->checkRequest($userData['token'], $results);
    }
}
