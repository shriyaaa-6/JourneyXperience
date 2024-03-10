<?php
// Replace these values with your actual database credentials
$servername = "localhost";
$username = "signup";
$password = "";
$dbname = "explorer";

try {
    // Create connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Process and validate form data
        $username = htmlspecialchars(trim($username));
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $errors = [];
        if (empty($username)) {
            $errors[] = "Name is required";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid email is required";
        }
        if (empty($password)) {
            $errors[] = "Password is required";
        }

        // If there are no errors, insert data into the database
        if (empty($errors)) {
            // Prepare and execute the query to insert into the database (without hashing the password)
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password); // Note: Not hashed, plaintext password (not recommended)
            $stmt->execute();

            // Redirect to index.php after successful registration with success parameter
            header("Location: index.php?success=Registration%20successful!");
            exit();
        } else {
            // Display errors
            foreach ($errors as $error) {
                echo "<p>Error: $error</p>";
            }
        }
    } else {
        echo "Form submission error!";
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>