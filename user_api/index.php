<?php
require_once 'functions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if 'endpoint' is set in either $_GET or $_POST
if (isset($_GET['endpoint'])) {
    $endpoint = $_GET['endpoint'];
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    
} elseif (isset($_POST['endpoint'])) {
    $endpoint = $_POST['endpoint'];
    $requestMethod = $_SERVER['REQUEST_METHOD'];
}
else {
    http_response_code(400);
    echo json_encode(array('error' => 'Missing endpoint parameter'));
    exit; // Stop further execution
}

switch ($requestMethod) {
    case 'GET':
        if ($endpoint == 'get_all_users') {
            getAllUsers();
        } elseif ($endpoint == 'get_user_by_id' && isset($_GET['id'])) {
            getUserById($_GET['id']);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(array('error' => 'Invalid endpoint or missing parameters'));
        }
        break;
    case 'POST':
        if ($endpoint == 'create_user') {
            createUser();
        } else {
            http_response_code(404); // Not Found
            echo json_encode(array('error' => 'Invalid endpoint'));
        }
        break;
    // ...(Add cases for PUT and DELETE methods)
    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(array('error' => 'Method not allowed'));
}
