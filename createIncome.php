<?php
    $conn = new mysqli("localhost", "root", "", "financial-tracker");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = (int) $_POST['id'] ?? null;
        $description = $_POST['description'] ?? null;
        $amount = $_POST['amount'] ?? null;
    
        if (empty($description) || empty($amount)) {
            echo json_encode(['message' => 'Description or amount not found.']);
            exit;
        }
    
        $stmt = $conn->prepare("INSERT INTO income (user_id, description, amount, time) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $id, $description, $amount);
    
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Income saved.']);
        } else {
            echo json_encode(['message' => 'Error saving income: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
    }
    
    $conn->close();
    ?>