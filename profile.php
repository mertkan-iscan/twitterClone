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
if (!$result) {
    die("Query failed: " . $conn->error);
}
$user = $result->fetch_assoc();

// Get user's tweets
$sql = "SELECT * FROM TWEETS WHERE user_id = '{$user['user_id']}' ORDER BY tweet_date DESC";
$result = $conn->query($sql);

// Calculate followers count
$sqlFollowers = "SELECT COUNT(*) AS followerCount FROM FOLLOWS WHERE followed_id = {$user['user_id']}";
$resultFollowers = $conn->query($sqlFollowers);
$followersCount = $resultFollowers->fetch_assoc()['followerCount'];

// Calculate following count
$sqlFollowing = "SELECT COUNT(*) AS followingCount FROM FOLLOWS WHERE follower_id = {$user['user_id']}";
$resultFollowing = $conn->query($sqlFollowing);
$followingCount = $resultFollowing->fetch_assoc()['followingCount'];

$conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Profile Page</title>
  </head>
  <body>
    <div class="container">
      <div class="card">
        <h1>Welcome to your profile, <?php echo $user['name']; ?></h1>

        <p>Username: <?php echo $user['username']; ?></p>

        <p>Followers: <?php echo $followersCount; ?></p>
        <p>Following: <?php echo $followingCount; ?></p>

        <form method="post" action="post_tweet.php">
          <label for="tweet">What's happening?</label>
          <textarea id="tweet" name="tweet" required></textarea>
          <input type="submit" value="Tweet">
        </form>

        <h2>Your Tweets:</h2>

        <?php
          if ($result->num_rows > 0) {
              // Output data for each tweet
              while($tweet = $result->fetch_assoc()) {
                  echo '<div>';
                  echo '<p>' . $tweet['content'] . '</p>';
                  echo '<p>Posted on: ' . $tweet['tweet_date'] . '</p>';
                  echo '</div>';
              }
          } else {
              echo 'You have not posted any tweets yet.';
          }
        ?>
      </div>
    </div>
  </body>
</html>
