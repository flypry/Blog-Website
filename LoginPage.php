<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="wrapper">
        <form action="LoginPage.php" method="post">
            <h1>Login</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                </svg>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lock" viewBox="0 0 16 16">
                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m0 5.996V14H3s-1 0-1-1 1-4 6-4q.845.002 1.544.107a4.5 4.5 0 0 0-.803.918A11 11 0 0 0 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664zM9 13a1 1 0 0 1 1-1v-1a2 2 0 1 1 4 0v1a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zm3-3a1 1 0 0 0-1 1v1h2v-1a1 1 0 0 0-1-1"/>
                </svg>
            </div>
            <div class="remember-forgot">
                <label><input type="checkbox">Remember me</label>
                <a href="#">Forgot password?</a>
            </div>
            <button type="submit" class="btn" name="submit">Login</button>
            <div class="register-link">
                <p>Don't have an account, yet? <a href="Signup.html">Create an Account</a></p>
            </div>
        </form>
        <?php
session_start(); // Start session for storing user data

// Database configuration
$servername = "localhost"; // Change this to your MySQL server hostname
$username = "username"; // Change this to your MySQL username
$password = "password"; // Change this to your MySQL password
$database = "users_info"; // Change this to your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if(isset($_POST['submit'])) {
    // Retrieving user inputs
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Basic validation
    if(empty($username) || empty($password)) {
        echo '<div class="error-message">Please enter both username and password.</div>';
    } else {
        // Prepare SQL statement to select user from database using a prepared statement
        $stmt = $conn->prepare("SELECT username, password FROM information WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User found, verify password
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
            if(password_verify($password, $hashed_password)) {
                // Password is correct, redirect to welcome page
                $_SESSION['username'] = $username; // Store username in session for future use
                header("Location: welcome.php");
                exit;
            } else {
                // Password is incorrect, display error message
                echo '<div class="error-message">Invalid password.</div>';
            }
        } else {
            // User not found, display error message
            echo '<div class="error-message">User not found.</div>';
        }
    }
}

// Close connection
$conn->close();
?>


    </div>
</body>

</html>
