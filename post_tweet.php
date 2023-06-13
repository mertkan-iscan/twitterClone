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

// Get user profile info
$username = $_SESSION['login_user'];
$sql = "SELECT * FROM USERS WHERE username = '$username'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// If a new tweet is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tweet = mysqli_real_escape_string($conn, $_POST['tweet']);

    // SQL query to insert new tweet
    $sql = "INSERT INTO TWEETS (user_id, content, tweet_date) VALUES ('{$user['user_id']}', '$tweet', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: profile.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>

