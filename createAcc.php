<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "financial-tracker";

$conn = new mysqli($servername, $username, $password, $dbname);

// if ($error) {
//     echo json_encode(['success' => false, 'message' => 'Error message']);
//     exit();
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (empty($fullname) || empty($user) || empty($pass)) {
        echo json_encode([
            "success" => false,
            "message" => "fullname, username, and password cannot be empty."
        ]);
        exit();
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
    $stmt->bind_param("s", $user);
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

    $stmt = $conn->prepare("INSERT INTO user (fullname, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $user, $pass);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Sign-up successful!"
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