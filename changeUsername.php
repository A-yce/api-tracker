<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "financial-tracker";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) $_POST['id'];
    $username = $_POST['username'];

    if (empty($username)) {
        echo json_encode([
            "success" => false,
            "message" => "Old and new usernames cannot be empty."
        ]);
        exit();
    }

    // Check if new username already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Username already exists."
        ]);
        exit();
    }

    // Update the username
    $stmt = $conn->prepare("UPDATE user SET username = ? WHERE id = ?");
    $stmt->bind_param("ss", $username, $id);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Username change successful!"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error: " . $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}

$conn->close();
?>
