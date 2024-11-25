<?php
session_start(); // Start the session to store session data

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "interactive study planner"; // Replace with your database name
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prevent SQL injection by escaping special characters
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    // Prepare SQL query to check if the user exists with the provided email
    $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Bind the email parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user is found with the provided email
    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();
        $db_password = $user['password']; // Password stored in the database

        // Verify if the entered password matches the hashed password in the database
        if (password_verify($password, $db_password)) {
            // Password is correct, start a session and redirect the user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            // Redirect to the profile or dashboard page
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect password
            $error_message = "Invalid email or password.";
        }
    } else {
        // User not found
        $error_message = "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
}
?>





