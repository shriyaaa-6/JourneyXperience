<?php
session_start(); // Start the session

$servername = "localhost"; // Your MySQL server name
$username = "user"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "explorer"; // Your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Authentication successful
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("location: logsucess.html"); // Redirect to account page upon successful login
    } else {
        echo "Invalid username or password";
    }

    $stmt->close();
}

$conn->close();
?>
