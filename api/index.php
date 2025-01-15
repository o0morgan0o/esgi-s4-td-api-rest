<?php
declare(strict_types=1);

ini_set("display_errors", "On");

require_once dirname(__DIR__) . "/vendor/autoload.php";

set_exception_handler([ErrorHandler::class, "handleException"]);
set_error_handler([ErrorHandler::class, "handleError"]);


$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// décomposition de l'url
$parts = explode("/", $path);

$resource = $parts[2] ?? null;

$id = $parts[3] ?? null;

switch ($resource) {
    case "users":
        $database = new Database();
        $userGateway = new UserGateway($database->getConnection());
        $controller = new UserController($userGateway);
        $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        break;
    default:
        http_response_code(404);
        echo json_encode(["message" => "Not Found"]);
        exit;
}