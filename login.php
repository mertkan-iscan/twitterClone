<?php
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

    // Retrieve username and password sent from index.html form
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Validate the username and password
        $sql = "SELECT user_id FROM USERS WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Fetch the associated array
            $row = $result->fetch_assoc();
            
            // Start the session
            session_start();
            $_SESSION['login_user'] = $username;
            // Store user_id in the session
            $_SESSION['login_user_id'] = $row['user_id'];

            // Redirect to the homepage
            header('Location: homepage.php');
        } else {
            $error = "Your Login Name or Password is invalid";
        }
    }

    $conn->close();

    // Including the HTML file
    include('index.html');
?>
