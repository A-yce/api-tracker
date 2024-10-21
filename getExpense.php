<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "financial-tracker";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request is a GET request
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $id = $_GET['id'] ?? null;

    // Validate the input
    if (empty($id)) {
        echo json_encode(['message' => 'ID not found.']);
        exit;
    }

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM expenses WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch data
    $expenses = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Return data
    echo json_encode(['message' => $expenses]);
} else {
    // Not a GET request
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}

// Close connection
$conn->close();
?>
