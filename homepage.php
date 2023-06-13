<!DOCTYPE html>
<html>
  <head>
    <title>Home Page</title>
  </head>
  <body>
    <div class="container">
      <div class="card">
        <?php
            // Start the session
            session_start();

            // Check if the user is logged in, if not redirect to the login page
            if(!isset($_SESSION['login_user'])){
                header("location: index.html");
                die();
            }
            echo '<h1>Welcome ' . $_SESSION['login_user'] . '</h1>';

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

            /// Get current user's ID
            $current_username = $_SESSION['login_user'];
            $sql = "SELECT user_id FROM USERS WHERE username = '$current_username'";
            $result = $conn->query($sql);

            if ($result === false) {
                die('Error: ' . $conn->error);
            }
        
            $user_id = $result->fetch_assoc()['user_id'];
        ?>

        <form method="post">
            <input type="text" name="searchUser" required>
            <input type="submit" value="Search">
        </form>

        <?php
          // If a search is performed
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              $searchedUser = mysqli_real_escape_string($conn, $_POST['searchUser']);
              // SQL query to search for the user
              $sql = "SELECT * FROM USERS WHERE username LIKE '%$searchedUser%'";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                  // User is found
                  while($row = $result->fetch_assoc()) {
                    echo '<div>';
                    echo '<p>Name: ' . $row['name'] . '</p>';
                    echo '<form method="post" action="follow.php">';
                    echo '<input type="hidden" name="followed_id" value="' . $row['user_id'] . '">';
                    echo '<input type="submit" value="Follow">';
                    echo '</form>';
                    echo '</div>';
                  }
              } else {
                  $error = "No user found";
              }
          }
        ?>

        <!-- Display error if user not found -->
        <?php if (!empty($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>

        <?php
          // SQL query to get tweets from followed users
          $sql = "SELECT TWEETS.* FROM TWEETS 
                  JOIN FOLLOWS ON TWEETS.user_id = FOLLOWS.followed_id 
                  WHERE FOLLOWS.follower_id = {$user_id} 
                  ORDER BY TWEETS.tweet_date DESC";
          $result = $conn->query($sql);
      
          if ($result === false) {
              die('Error: ' . $conn->error);
          }
          if ($result->num_rows > 0) {
              echo '<h2>Tweets from the users you are following</h2>';
              while($row = $result->fetch_assoc()) {
                  echo '<div>';
                  echo '<p>Tweet: ' . $row['content'] . '</p>';
                  echo '<p>Date: ' . $row['tweet_date'] . '</p>';
                  echo '</div>';
              }
          } else {
              echo 'No tweets from users you are following.';
          }

          $conn->close();
        ?>

        <form method="post" action="logout.php">
          <input type="submit" value="Logout">
        </form>

        <form method="get" action="profile.php">
          <input type="submit" value="Profile">
        </form>

      </div>
    </div>
  </body>
</html>
