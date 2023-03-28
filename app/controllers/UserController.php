<?php
declare(strict_types=1);

namespace controllers;

use system\Request;
use models\GorestAPI;
use models\User;
use Twig\Error\Error;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      title="USERS REST API",
 *      version="1.0.0",
 *      description="Endpoints of users API",
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 */
class UserController
{
    /**
     * @OA\Schema(
     *     schema="User",
     *     title="User",
     *     description="User object",
     *     required={"id", "name", "email", "status", "gender"},
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="User ID",
     *         readOnly=true,
     *     ),
     *     @OA\Property(
     *         property="name",
     *         type="string",
     *         description="User name",
     *     ),
     *     @OA\Property(
     *         property="email",
     *         type="string",
     *         format="email",
     *         description="User email",
     *     ),
     *     @OA\Property(
     *         property="status",
     *         type="string",
     *         description="User status",
     *         enum={"active", "inactive"},
     *     ),
     *     @OA\Property(
     *         property="gender",
     *         type="string",
     *         description="User gender",
     *         enum={"male", "female"},
     *     )
     * )
     */
    static array $USER_FIELDS = [
        'status' => 'status',
        'gender' => 'gender',
        'name' => 'name',
        'email' => 'email'
    ];

    static array $STATUS_OPTIONS = [
        'active' => 'active',
        'inactive' => 'inactive'
    ];

    static array $GENDER_OPTIONS = [
        'male' => 'male',
        'female' => 'female'
    ];

    private User $user;
    private GorestAPI $api;
    public string $content;

    public function __construct()
    {
        $this->setUser(new User());
        $this->setGorestAPI(new GorestAPI());
        $this->content = '';
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setGorestAPI(GorestAPI $api): void
    {
        $this->api = $api;
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Get a list of users with pagination",
     *     description="Retrieve a list of users with pagination. Use the `page` parameter to specify the page number.",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number to retrieve",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             minimum=1,
     *             example=1,
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="No more records",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="There are no more records to load",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Server error occurred",
     *             )
     *         )
     *     ),
     * )
     */
    public function getRecords(int $pageNumber, int $pageLimit, Request $request): void
    {
        if ($this->isExternalAPI($request->getCookies())) {
            $data = $this->api->getRecords($pageNumber, $pageLimit);
        } else {
            $data = $this->user->getRecordsWithLimitAndOffset($pageNumber, $pageLimit);
        }
        $this->sendData($data[0], $data[1]);
    }

    /**
     * @OA\Get(
     *     path="/user/show/{id}",
     *     summary="Get a single user",
     *     description="Get a single user by id",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of user to receive",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *             minimum=1,
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="There is no user with id {id}",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Server error occurred",
     *             )
     *         )
     *     )
     * )
     */
    public function getOne(int $id, Request $request): void
    {
        if ($this->isExternalAPI($request->getCookies())) {
            $data = $this->api->getOne($id);
        } else {
            $data = $this->user->getOne($id);
        }
        $this->sendData($data[0], $data[1]);
    }

    /**
     * @OA\Post(
     *     path="/users/create",
     *     summary="Create a new user",
     *     description="Create a new user with a specified name, email, gender and status",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User object that needs to be created",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="User created",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Please select a correct status (active or inactive)",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Client error: `POST resulted in a `422 Unprocessable Entity`",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'usher@example.com' for key 'users.email'",
     *             )
     *         )
     *     )
     * )
     */
    public function create(Request $request): void
    {
        $postParams = $request->getPostParams();

        if ($this->validate($postParams)) {
            if ($this->isExternalAPI($request->getCookies())) {
                $data = $this->api->create($postParams);
            } else {
                $data = $this->user->create($postParams);
            }
            $this->sendData($data[0], $data[1]);
        }
    }

    /**
     * @OA\Patch(
     *     path="/users/update/{id}",
     *     summary="Update user",
     *     description="Update user fields (name, email, gender, status)",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User's id for update",
     *         @OA\Schema(
     *             type="integer",
     *             minimum=1,
     *             example=1,
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated user",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Please enter a the name",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="The user with such id not found",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'user@example.com' for key 'users.email'"
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, int $id): void
    {
        $postParams = $request->getPostParams();
        if ($this->validate($postParams)) {
            if ($this->isExternalAPI($request->getCookies())) {
                $data = $this->api->update($id, $postParams);
            } else {
                $data = $this->user->update($id, $postParams);
            }
            $this->sendData($data[0], $data[1]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Delete user",
     *     description="Deletes user by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The id of the user to delete",
     *         @OA\Schema(
     *             type="integer",
     *             minimum=1,
     *             example=1,
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="User deleted succefully",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="There is no user with id 2",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal server error",
     *     )
     * )
     */
    public function delete(int $id, Request $request): void
    {
        if ($this->isExternalAPI($request->getCookies())) {
            $data = $this->api->deleteOne($id);
        } else {
            $data = $this->user->delete($id);
        }
        $this->sendData($data[0], $data[1]);
    }

    /**
     * @OA\Post(
     *     path="/users/delete",
     *     summary="Delete multiple users",
     *     description="Delete multiple users by providing their ids",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="IDs of the users to delete, separated by commas",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"ids"},
     *             @OA\Property(
     *                 property="ids",
     *                 type="array",
     *                 @OA\Items(
     *                     type="integer",
     *                     example="1"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Success, no content returned"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Invalid input, IDs must be integers",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User(s) not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="User(s) not found",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Failed to delete records"
     *             )
     *         )
     *     )
     * )
     */
    public function deleteMultiple(Request $request): void
    {
        $ids = $request->getPostParams()['ids'] ?? [];

        if ($this->isExternalAPI($request->getCookies())) {
            $data = $this->api->deleteMultiple($ids);
        } else {
            $data = $this->user->deleteMultiple($ids);
        }
        $this->sendData($data[0], $data[1]);
    }

    public function index(): void
    {
        $this->render('index');
    }

    public function show(): void
    {
        $this->render('show', [
            "REFERER" => $_SERVER['HTTP_REFERER']
        ]);
    }

    public function new(): void
    {
        $this->render('new', [
            "GENDER_OPTIONS" => self::$GENDER_OPTIONS,
            "STATUS_OPTIONS" => self::$STATUS_OPTIONS,
            "REFERER" => $_SERVER['HTTP_REFERER']
        ]);
    }

    public function edit(): void
    {
        $this->render('edit', [
            "GENDER_OPTIONS" => self::$GENDER_OPTIONS,
            "STATUS_OPTIONS" => self::$STATUS_OPTIONS,
            "USER_FIELDS" => self::$USER_FIELDS,
            "REFERER" => $_SERVER['HTTP_REFERER']
        ]);
    }

    public function countAllRecords(): void
    {
        $this->user->countAllRecords();
    }

    public function validate(array $data): bool
    {
        $name = $data[self::$USER_FIELDS["name"]];
        $email = $data[self::$USER_FIELDS["email"]];
        $gender = $data[self::$USER_FIELDS["gender"]];
        $status = $data[self::$USER_FIELDS["status"]];

        if (empty($name)) {
            $this->sendData(['error' => 'Please enter a the name'], 400);
            return false;
        }

        if (strlen($name) < 2) {
            $this->sendData(['error' => 'Name must have at least 3 characters'], 400);
            return false;
        }

        if (empty($email)) {
            $this->sendData(['error' => 'Please enter a email'], 400);
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendData(['error' => 'Please enter a valid email address'], 400);
            return false;
        }

        if (!in_array($gender, UserController::$GENDER_OPTIONS)) {
            $this->sendData(['error' => 'Please select a correct gender (male or female)'], 400);
            return false;
        }

        if (!in_array($status, UserController::$STATUS_OPTIONS)) {
            $this->sendData(['error' => 'Please select a correct status (active or inactive)'], 400);
            return false;
        }
        return true;
    }

    public function sendData(array|null|string $data, int $statusCode): void
    {
        http_response_code($statusCode);
        if (!empty($data) && is_array($data)) {
            echo json_encode($data);
        } elseif (is_string($data)) {
            echo $data;
        }
    }

    public function isExternalAPI(array $cookies): bool
    {
        if (isset($cookies['dataSource'])) {
            return $cookies['dataSource'] === 'gorest';
        }
        return false;
    }

    private function render(string $filename, array $data = []): void
    {
        global $twig;

        try {
            echo $twig->render("users/$filename.twig", $data);
        } catch (Error $e) {
            echo "Something went wrong: " . $e->getMessage();
        }
    }
}
