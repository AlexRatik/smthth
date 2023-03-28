<?php declare(strict_types=1);

use models\GorestAPI;
use PHPUnit\Framework\TestCase;

class GorestAPITest extends TestCase
{
    private GorestAPI $api;

    protected function setUp(): void
    {
        $this->api = new GorestAPI();
    }

    public function testGetRecords(): array
    {
        $data = $this->api->getRecords(1, 10);
        $records = json_decode($data[0], true);

        $this->assertSame(10, sizeof($records));
        $this->assertSame(200, $data[1]);
        return $records;
    }

    public function testGetRecordsFromInvalidPage(): void
    {
        $response = $this->api->getRecords(999999999, 10);

        $this->assertArrayHasKey('error', $response[0]);
        $this->assertSame(404, $response[1]);
    }

    /**
     * @depends testGetRecords
     **/
    public function testGetOne(array $records): array
    {
        foreach ($records as $record) {
            $response = $this->api->getOne($record['id']);
            $responseParams = json_decode($response[0], true);
            $this->assertSame($record, $responseParams);
            $this->assertSame(200, $response[1]);
        }
        return $records[0];
    }

    public function testGetInvalidOne(): void
    {
        $response = $this->api->getOne(-1);

        $this->assertArrayHasKey('error', $response[0]);
        $this->assertSame(404, $response[1]);
    }

    /**
     * @depends      testGetOne
     * @dataProvider getRecords
     **/
    public function testCreate(): void
    {
        $args = array_slice(func_get_args(), 0, 3);

        foreach ($args as $params) {
            $params['email'] = $this->generateRandomEmail();
            $response = $this->api->create($params);
            $responseParams = json_decode($response[0], true);

            $this->assertArrayHasKey('id', $responseParams);

            unset($responseParams['id']);

            $this->assertEquals($params, $responseParams);
            $this->assertSame(201, $response[1]);
        }
    }

    /**
     * @dataProvider getRecords
     **/
    public function testCreateInvalidUser(array $params): void
    {
        $params['email'] = $this->generateRandomEmail();
        $this->api->create($params);
        $response = $this->api->create($params);

        $this->assertArrayHasKey('error', $response[0]);
        $this->assertSame(422, $response[1]);
    }

    /**
     * @depends testGetOne
     * @depends testCreate
     **/
    public function testUpdate(array $record): void
    {
        $updatedRecord = [
            'name' => 'John Doe',
            'email' => $this->generateRandomEmail(),
            'gender' => 'male',
            'status' => 'active'
        ];

        $response = $this->api->update($record['id'], $updatedRecord);
        $responseParams = json_decode($response[0], true);

        $this->assertSame($responseParams['id'], $record['id']);

        unset($responseParams['id']);

        $this->assertEquals($updatedRecord, $responseParams);
        $this->assertSame(200, $response[1]);
    }

    /**
     * @dataProvider getRecords
     **/
    public function testUpdateInvalidUser(array $params): void
    {
        $response = $this->api->update(-1, $params);

        $this->assertArrayHasKey('error', $response[0]);
        $this->assertSame(404, $response[1]);
    }

    /**
     * @depends testGetRecords
     * @depends testUpdate
     **/
    public function testDeleteOne(array $records): void
    {
        for ($i = 4; $i < 6; $i++) {
            $response = $this->api->deleteOne($records[$i]['id']);
            $this->assertEquals([], $response[0]);
            $this->assertSame(204, $response[1]);
        }
    }

    public function testDeleteInvalidUser(): void
    {
        $response = $this->api->deleteOne(-1);

        $this->assertArrayHasKey('error', $response[0]);
        $this->assertSame(404, $response[1]);
    }

    /**
     * @depends testGetRecords
     * @depends testDeleteOne
     **/
    public function testDeleteMultiple(array $records): void
    {
        $ids = [];
        foreach ($records as $record) {
            $ids[] = $record['id'];
        }
        $this->assertNotEmpty($ids);
        $response = $this->api->deleteMultiple(array_slice($ids, 1, 3));

        $this->assertSame(204, $response[1]);
        $this->assertEmpty($response[0]);
    }

    public function testDeleteInvalidMultiple(): void
    {
        $ids = [-1, 2, 3];
        $this->assertNotEmpty($ids);
        $response = $this->api->deleteMultiple($ids);

        $this->assertArrayHasKey('error', $response[0]);
        $this->assertSame(404, $response[1]);
    }

    public function generateRandomEmail($length = 10): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString . '@example.com';
    }


    public function getRecords(): array
    {
        return [
            [
                [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'gender' => 'male',
                    'status' => 'active'
                ],
                [
                    'name' => 'Jahn Doe',
                    'email' => 'jahn@example.com',
                    'gender' => 'female',
                    'status' => 'inactive'
                ],
                [
                    'name' => 'John Dol',
                    'email' => 'john_dol@example.com',
                    'gender' => 'male',
                    'status' => 'active'
                ]
            ]
        ];
    }
}