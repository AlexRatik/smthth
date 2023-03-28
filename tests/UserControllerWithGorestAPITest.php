<?php declare(strict_types=1);

use controllers\UserController;
use models\GorestAPI;
use PHPUnit\Framework\TestCase;
use system\Request;

class UserControllerWithGorestAPITest extends TestCase
{
    private UserController $controller;

    private int $id;

    private GorestAPI $gorestAPI;

    private Request $request;

    protected function setUp(): void
    {
        $this->id = 1;

        $this->controller = new UserController();

        $this->gorestAPI = $this->getMockBuilder(GorestAPI::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller->setGorestAPI($this->gorestAPI);

        $this->request = $this->createMock(Request::class);
        $this->request->expects($this->once())
            ->method('getCookies')
            ->willReturn([
                'dataSource' => 'gorest'
            ]);
    }

    /**
     * @dataProvider getRecords
     **/
    public function testGetOne(array $user)
    {
        $this->gorestAPI->expects($this->once())
            ->method('getOne')
            ->with($this->id)
            ->willReturn([$user, 200]);

        $this->controller->getOne($this->id, $this->request);

        $this->expectOutputString(json_encode($user));
    }

    /**
     * @dataProvider getRecords
     **/
    public function testGetRecordsFromValidPage()
    {
        $args = func_get_args();

        $this->gorestAPI->expects($this->once())
            ->method('getRecords')
            ->with(1, 10)
            ->willReturn([$args, 200]);

        $this->controller->getRecords(1, 10, $this->request);

        $this->expectOutputString(json_encode($args));
    }

    /**
     * @dataProvider getRecords
     */
    public function testCreateUser(array $data)
    {
        $user = $data;
        unset($user['id']);

        $this->request->expects($this->once())
            ->method('getPostParams')
            ->willReturn($user);

        $this->gorestAPI->expects($this->once())
            ->method('create')
            ->with($user)
            ->willReturn([$user, 201]);

        $this->controller->create($this->request);

        $this->expectOutputString(json_encode($user));
    }

    /**
     * @dataProvider getRecords
     **/
    public function testUserUpdate(array $user, array $w)
    {
        $this->request->expects($this->once())
            ->method('getPostParams')
            ->willReturn($user);

        $this->gorestAPI->expects($this->once())
            ->method('update')
            ->with($this->id, $user)
            ->willReturn([$user, 200]);

        $this->controller->update($this->request, $this->id);

        $this->expectOutputString(json_encode($user));
    }

    public function testUserDelete()
    {
        $this->gorestAPI->expects($this->once())
            ->method('deleteOne')
            ->with($this->id)
            ->willReturn([[], 204]);

        $this->controller->delete($this->id, $this->request);

        $this->expectOutputString('');
    }

    public function testDeletionOfNonExistentUser()
    {
        $this->gorestAPI->expects($this->once())
            ->method('deleteOne')
            ->with($this->id)
            ->willReturn([['error' => "There is no user with id " . $this->id], 404]);

        $this->controller->delete($this->id, $this->request);

        $this->expectOutputString('{"error":"There is no user with id ' . $this->id . '"}');
    }

    public function testDeletionOfMultipleOfUsers()
    {
        $ids = [1, 2, 3];
        $this->request->expects($this->once())
            ->method('getPostParams')
            ->willReturn(['ids' => $ids]);

        $this->gorestAPI->expects($this->exactly(1))
            ->method('deleteMultiple')
            ->with($ids)
            ->willReturn([[], 204]);

        $this->controller->deleteMultiple($this->request);
        $this->expectOutputString('');

        $this->expectOutputString('');
    }

    public function testDeletionOfMultipleOfUsersWithInvalidInput()
    {
        $ids = [];

        $this->request->expects($this->once())
            ->method('getPostParams')
            ->willReturn(['ids' => $ids]);

        $this->controller->setGorestAPI(new GorestAPI());
        $this->controller->deleteMultiple($this->request);

        $this->expectOutputString(json_encode(['error' => 'Invalid input']));
    }

    public function getRecords(): array
    {
        return [
            [
                [
                    'id' => 1,
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'gender' => 'male',
                    'status' => 'active'
                ],
                [
                    'id' => 2,
                    'name' => 'Jahn Doe',
                    'email' => 'jahn@example.com',
                    'gender' => 'female',
                    'status' => 'inactive'
                ],
                [
                    'id' => 3,
                    'name' => 'John Dol',
                    'email' => 'john_dol@example.com',
                    'gender' => 'male',
                    'status' => 'active'
                ]
            ]
        ];
    }
}
