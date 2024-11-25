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
    $profilePic = null;

    // Handle file upload if a file is selected
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        $profilePic = $uploadDir . basename($_FILES['profile_pic']['name']);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilePic);
    }

    // Prepare SQL query
    $sql = "INSERT INTO user_profiles (username, email, profile_pic) 
            VALUES ('$username', '$email', '$profilePic')
            ON DUPLICATE KEY UPDATE 
            username = VALUES(username),
            email = VALUES(email),
            profile_pic = VALUES(profile_pic)";

    // Execute SQL query
    if ($conn->query($sql) === TRUE) {
        echo "Profile updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
