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

    // Retrieve name, username and password sent from index.html form
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Validate if the username already exists
        $sql = "SELECT user_id FROM USERS WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            // Insert new user
            $sql = "INSERT INTO USERS (name, username, password) VALUES ('$name', '$username', '$password')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
                // Redirect to the login page
                header('Location: index.html');
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $error = "Username already exists";
        }
    }

    $conn->close();
?>
