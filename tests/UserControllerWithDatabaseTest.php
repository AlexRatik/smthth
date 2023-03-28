<?php declare(strict_types=1);

use commands\Database;
use controllers\UserController;
use models\User;
use PHPUnit\Framework\TestCase;
use system\Request;

class UserControllerWithDatabaseTest extends TestCase
{
    private UserController $controller;

    private Database $db;

    private string $table;

    private int $id;

    private Request $request;

    protected function setUp(): void
    {
        $this->table = 'users';
        $this->id = 1;

        $this->controller = new UserController();

        $this->db = $this->getMockBuilder(Database::class)
            ->disableOriginalConstructor()
            ->getMock();

        $user = new User();
        $user->setDatabase($this->db);

        $this->controller->setUser($user);

        $this->request = $this->createMock(Request::class);
        $this->request->expects($this->once())
            ->method('getCookies')
            ->willReturn(['dataSource' => 'local']);
    }

    /**
     * @dataProvider getRecords
     **/
    public function testGetOne(array $user)
    {
        $this->db->expects($this->once())
            ->method('getOne')
            ->with($this->table, $this->id)
            ->willReturn($user);

        $this->controller->getOne($this->id, $this->request);

        $this->expectOutputString(json_encode($user));
    }

    /**
     * @dataProvider getRecords
     **/
    public function testGetRecordsFromValidPage()
    {
        $args = func_get_args();

        $this->db->expects($this->once())
            ->method('getRecordsWithLimitAndOffset')
            ->with(10, 0, $this->table)
            ->willReturn($args);

        $this->controller->getRecords(1, 10, $this->request);

        $this->expectOutputString(json_encode($args));
    }

    /**
     * @dataProvider getRecords
     */
    public function testCreateUser(array $user)
    {
        $this->request->expects($this->once())
            ->method('getPostParams')
            ->willReturn($user);

        $this->db->expects($this->once())
            ->method('insert')
            ->with($this->table, $user);

        $this->controller->create($this->request);

        $this->expectOutputString(json_encode($user));
    }

    /**
     * @dataProvider getRecords
     **/
    public function testUserUpdate(array $user)
    {
        $this->request->expects($this->once())
            ->method('getPostParams')
            ->willReturn($user);

        $this->db->expects($this->once())
            ->method('update')
            ->with($this->table, $this->id, $user);

        $this->controller->update($this->request, $this->id);

        $this->expectOutputString(json_encode($user));
    }

    /**
     * @dataProvider getRecords
     **/
    public function testDeletionOfUser(array $user)
    {
        $this->db->expects($this->once())
            ->method('getOne')
            ->with($this->table, $this->id)
            ->willReturn($user);

        $this->db->expects($this->once())
            ->method('delete')
            ->with($this->table, $this->id)
            ->willReturn(new PDOStatement());

        $this->controller->delete($this->id, $this->request);

        $this->expectOutputString('');
    }

    public function testDeletionOfNonExistentUser()
    {
        $this->db->expects($this->never())
            ->method('delete')
            ->with($this->table, $this->id);

        $this->controller->delete($this->id, $this->request);

        $this->expectOutputString('{"error":"There is no user with id ' . $this->id . '"}');
    }

    public function testDeleteMultipleOfUsers()
    {
        $ids = [1, 2, 3];
        $this->request->expects($this->once())
            ->method('getPostParams')
            ->willReturn(['ids' => $ids]);

        $mockPDOStatement = $this->createMock(PDOStatement::class);
        $mockPDOStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(3);

        $this->db->expects($this->once())
            ->method('deleteMultiple')
            ->with($this->table, $ids)
            ->willReturn($mockPDOStatement);

        $this->controller->deleteMultiple($this->request);
        $this->expectOutputString('');
    }

    public function testDeleteMultipleOfUsersWithInvalidInput()
    {
        $ids = [];

        $this->request->expects($this->once())
            ->method('getPostParams')
            ->willReturn(['ids' => $ids]);

        $this->db->expects($this->never())
            ->method('deleteMultiple');

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