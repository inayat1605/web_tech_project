<?php
// Database connection
$servername = "localhost"; // Change this to your database server if needed
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "your_database"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define error messages array
$errorMessages = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    
    // Username validation
    if (empty($username)) {
        $errorMessages[] = "Username is required.";
    } else {
        // Check if the username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errorMessages[] = "Username already exists.";
        }
    }
    
    // Password validation
    if (empty($password)) {
        $errorMessages[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errorMessages[] = "Password must be at least 6 characters long.";
    }
    
    // Confirm password validation
    if (empty($confirmPassword)) {
        $errorMessages[] = "Please confirm your password.";
    } elseif ($password !== $confirmPassword) {
        $errorMessages[] = "Passwords do not match.";
    }
    
    // If no errors, proceed to register user
    if (count($errorMessages) === 0) {
        // Hash password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);

        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful!";
            // Redirect to login page or other page
            header("Location: login.html");
            exit();
        } else {
            $errorMessages[] = "Error: " . $stmt->error;
        }
    }
}

// Close connection
$conn->close();
?>




