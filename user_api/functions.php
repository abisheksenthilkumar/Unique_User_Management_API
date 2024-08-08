<?php
require_once 'config.php';

// Function to establish a database connection
function connectToDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to get all users
function getAllUsers() {
    $conn = connectToDatabase();
    $result = $conn->query("SELECT * FROM users");

    $users = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($users);
    $conn->close();
}

// Function to get a specific user by ID
function getUserById($id) {
    $conn = connectToDatabase();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo json_encode($user);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(array('error' => 'User not found'));
    }

    $stmt->close();
    $conn->close();
}

// Function to create a new user
function createUser() {
    $data = json_decode(file_get_contents("php://input"), true);

    // Input Validation
    if (!isset($data['username'], $data['email'], $data['password']) || 
        empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        http_response_code(400); // Bad Request
        echo json_encode(array('error' => 'Missing or empty required fields (username, email, password)'));
        return;
    }

    // Hash the password
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    $conn = connectToDatabase();
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $data['username'], $data['email'], $hashedPassword);

    if ($stmt->execute()) {
        http_response_code(201); // Created
        echo json_encode(array('message' => 'User created successfully'));
    } else {
        if ($stmt->errno == 1062) { // Duplicate entry error
            http_response_code(409); // Conflict
            echo json_encode(array('error' => 'Username or email already exists'));
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(array('error' => 'Failed to create user: ' . $stmt->error));
        }
    }
    $stmt->close();
    $conn->close();
}

// Function to update an existing user by ID
function updateUser($id) {
    $data = json_decode(file_get_contents("php://input"), true);

    // Input Validation
    if (!isset($data['username'], $data['email']) || empty($data['username']) || empty($data['email'])) {
        http_response_code(400); // Bad Request
        echo json_encode(array('error' => 'Missing or empty required fields (username, email)'));
        return;
    }

    $conn = connectToDatabase();
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $data['username'], $data['email'], $id);

    if ($stmt->execute()) {
        echo json_encode(array('message' => 'User updated successfully'));
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(array('error' => 'Failed to update user: ' . $stmt->error));
    }

    $stmt->close();
    $conn->close();
}


// Function to delete a user by ID
function deleteUser($id) {
    $conn = connectToDatabase();
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(array('message' => 'User deleted successfully'));
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(array('error' => 'Failed to delete user: ' . $stmt->error));
    }
    $stmt->close();
    $conn->close();
}
