<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once $_SERVER['DOCUMENT_ROOT']."/app/models/UserModel.php";

class UserController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    // Handle API requests
    public function processRequest() {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $uri = $_SERVER["REQUEST_URI"];

        if ($requestMethod === "POST") {
            $this->handlePostRequests($uri);
        } elseif ($requestMethod === "GET") {
            $this->handleGetRequests($uri);
        } else {
            $this->sendResponse(["message" => "Invalid request method"], 405);
        }
    }

    // Handle POST requests (Register & Login)
    private function handlePostRequests($uri) {
        $input = json_decode(file_get_contents("php://input"), true);

        if (strpos($uri, "/register") !== false) {
            $this->registerUser($input);
        } elseif (strpos($uri, "/login") !== false) {
            $this->loginUser($input);
        } else {
            $this->sendResponse(["message" => "Invalid POST request"], 400);
        }
    }

    // Handle GET requests (Protected Route)
    private function handleGetRequests($uri) {
        if (strpos($uri, "/protected") !== false) {
            $this->verifyAccess();
        } else {
            $this->sendResponse(["message" => "Invalid GET request"], 400);
        }
    }

    // Register a new user
    private function registerUser($input) {
        if (!isset($input['username'], $input['email'], $input['password'], $input["timestamp"])) {
            $this->sendResponse(["message" => "Missing required fields"], 400);
        }

        if ($this->user->register($input['username'], $input['email'], $input['password'],$input["timestamp"])) {
            $this->sendResponse(["message" => "Registration successful"], 201);
        } else {
            $this->sendResponse(["message" => "Registration failed"], 500);
        }
    }

    // Login user
    private function loginUser($input) {
        if (!isset($input['email'], $input['password'])) {
            $this->sendResponse(["message" => "Missing required fields"], 400);
        }

        $loginResponse = $this->user->login($input['email'], $input['password']);
        if ($loginResponse) {
            $this->sendResponse($loginResponse, 200);
        } else {
            $this->sendResponse(["message" => "Invalid credentials"], 401);
        }
    }

    // Verify token for protected route
    private function verifyAccess() {
        $headers = apache_request_headers();
        if (!isset($headers["Authorization"])) {
            $this->sendResponse(["message" => "No token provided"], 401);
        }

        $token = str_replace("Bearer ", "", $headers["Authorization"]);
        $userData = $this->user->verifyToken($token);

        if ($userData) {
            $this->sendResponse(["message" => "Access granted", "user" => $userData], 200);
        } else {
            $this->sendResponse(["message" => "Invalid or expired token"], 403);
        }
    }

    // Send JSON response
    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}

// Process incoming requests
$controller = new UserController();
$controller->processRequest();
?>
