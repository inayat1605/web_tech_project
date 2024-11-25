<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Update with your database password
$dbname = "interactive study planner";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists
    $checkEmailQuery = "SELECT email FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        echo "Error: Email is already registered.";
    } else {
        // Insert the user into the database
        $sql = "INSERT INTO users (username, email, password) 
                VALUES ('$username', '$email', '$hashedPassword')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful! You can now log in.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close the connection
$conn->close();
?>
