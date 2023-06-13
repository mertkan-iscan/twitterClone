<?php
// Start session
session_start();
if(!isset($_SESSION['login_user'])){
    header("location: index.html");
    die();
}

// Database connection
$host = 'localhost';  // Host name 
$username = 'root'; // MySQL username 
$password = '1'; // MySQL password 
$db_name = 'twitter_clone'; // Database name 

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged in user info
$username = $_SESSION['login_user'];
$sql = "SELECT * FROM USERS WHERE username = '$username'";
$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
$user = $result->fetch_assoc();

// Get followed user id
$followed_id = $_POST['followed_id'];

// Check if the current user already follows the target user
$sql = "SELECT * FROM FOLLOWS WHERE follower_id = '{$user['user_id']}' AND followed_id = '$followed_id'";
$result = $conn->query($sql);

if($result->num_rows > 0) {
    // User already follows the target user
    echo "You already follow this user.";
} else {
    // User does not follow the target user, so let's insert into the follows table
    $sql = "INSERT INTO FOLLOWS (follower_id, followed_id) VALUES ('{$user['user_id']}', '$followed_id')";

    if ($conn->query($sql) === TRUE) {
        echo "You are now following this user.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
