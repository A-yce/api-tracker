<?php
    $conn = new mysqli("localhost", "root", "", "financial-tracker");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT id FROM user WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id = $row['id']; 
            
            echo json_encode(array(
                "status" => "success",
                "id" => $id
            ));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
?>
