<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, DELETE, GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once $_SERVER['DOCUMENT_ROOT'] . "/app/models/FavoriteModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/models/UserModel.php";

class FavoriteController {
    private $favorite;
    private $user;

    public function __construct() {
        $this->favorite = new Favorite();
        $this->user = new User();
    }

    public function processRequest() {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $headers = apache_request_headers();
        if (!isset($headers["Authorization"])) {
            $this->sendResponse(["message" => "No token provided"], 401);
        }

        $token = str_replace("Bearer ", "", $headers["Authorization"]);
        $userData = $this->user->verifyToken($token);

        if (!$userData) {
            $this->sendResponse(["message" => "Invalid or expired token"], 403);
        }

        $userId = $userData["id"];

        if ($requestMethod === "POST") {
            $input = json_decode(file_get_contents("php://input"), true);
            if (!isset($input["title"], $input["year"], $input["type"], $input["poster"])) {
                $this->sendResponse(["message" => "Missing movie details"], 400);
            }
            $this->addFavorite($userId, $input);
        } elseif ($requestMethod === "DELETE") {
            $input = json_decode(file_get_contents("php://input"), true);
            if (!isset($input["title"], $input["year"])) {
                $this->sendResponse(["message" => "Title and Year are required"], 400);
            }
            $this->removeFavorite($userId, $input["title"], $input["year"]);
        } elseif ($requestMethod === "GET") {
            $this->getFavorites($userId);
        } else {
            $this->sendResponse(["message" => "Invalid request method"], 405);
        }
    }

    private function addFavorite($userId, $input) {
        if ($this->favorite->addFavorite($userId, $input["title"], $input["year"], $input["type"], $input["poster"])) {
            $this->sendResponse(["message" => "Movie added to favorites"], 201);
        } else {
            $this->sendResponse(["message" => "Failed to add favorite"], 500);
        }
    }

    private function removeFavorite($userId, $title, $year) {
        if ($this->favorite->removeFavorite($userId, $title, $year)) {
            $this->sendResponse(["message" => "Movie removed from favorites"], 200);
        } else {
            $this->sendResponse(["message" => "Failed to remove favorite"], 500);
        }
    }

    private function getFavorites($userId) {
        $favorites = $this->favorite->getFavorites($userId);
        $this->sendResponse($favorites, 200);
    }

    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}

$controller = new FavoriteController();
$controller->processRequest();
?>
