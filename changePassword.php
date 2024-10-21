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
    $password = $_POST['password'];

    if (empty($password)) {
        echo json_encode([
            "success" => false,
            "message" => "Old and new password cannot be empty."
        ]);
        exit();
    }

    // Check if new password already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE password = ?");
    $stmt->bind_param("s", $password);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode([
            "success" => false,
            "message" => "password already exists."
        ]);
        exit();
    }

    // Update the password
    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
    $stmt->bind_param("ss", $password, $id);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "password change successful!"
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
