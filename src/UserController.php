<?php
declare(strict_types=1);

class UserController
{
    public function __construct(private UserGateway $userGateway)
    {
    }

    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            $user = $this->userGateway->getById($id);
            if ($user === false) {
                $this->responseNotFound($id);
                return;
            }

            switch ($method) {
                case 'GET':
                    echo json_encode($user);
                    break;
                default:
                    $this->responseMethodNotAllowed("GET");

                // A VOUS DE CRÉER LE CODE POUR LA MÉTHODE PATCH (UPDATE)

                // A VOUS DE CRÉER LE CODE POUR LA MÉTHODE DELETE 
            }

        } else {

            switch ($method) {
                case 'GET':
                    echo json_encode($this->userGateway->getAll());
                    break;

                case 'POST':
                    $data = json_decode(file_get_contents("php://input"), true);
                    // on s'assure que les données sont valides
                    $errors = $this->getValidationErrors($data);
                    if (!empty($errors)) {
                        $this->responseValidationError($errors);
                        return;
                    }
                    $createdId = $this->userGateway->insert($data);
                    $this->responseCreated($createdId);
                    break;
                default:
                    $this->responseMethodNotAllowed("GET, POST");
            }
        }
    }
    public function getValidationErrors(array $data): array
    {
        $errors = [];
        if (empty($data["first_name"])) {
            $errors["first_name"] = "First name is required";
        }
        if (empty($data["last_name"])) {
            $errors["last_name"] = "Last name is required";
        }
        if (empty($data["email"])) {
            $errors["email"] = "Email is required";

        } elseif (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Email is invalid";
        }
        return $errors;

    }

    private function responseCreated(string $id): void
    {
        http_response_code(201); // CREATED RESPONSE CODE
        echo json_encode(["message" => "User Created", "id" => $id]);
    }

    private function responseMethodNotAllowed(string $allowedMethods): void
    {
        http_response_code(405); // METHOD NOT ALLOWED RESPONSE CODE
        header("Allow: $allowedMethods");
    }

    private function responseNotFound(string $id): void
    {
        http_response_code(404); // NOT FOUND RESPONSE CODE
        echo json_encode(["message" => "User $id Not Found"]);
    }

    private function responseValidationError(array $errors): void
    {
        http_response_code(422); // INVALID DATA RESPONSE CODE
        echo json_encode(["errors" => $errors]);
    }
}