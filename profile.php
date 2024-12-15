<?php
session_start();

// Check session data
if (!isset($_SESSION['user_id'])) {
    die("User is not logged in! Please log in.");
}

$user_id = $_SESSION['user_id'];

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "twinkl_app";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data by ID
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user data
} else {
    die("User not found.");
}

// Close the statement
$stmt->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Page</title>
  <style>
   * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background-color: #f8f9fa;
    min-height: 100vh; 
    display: flex;
    flex-direction: column;
    justify-content: space-between; 
}

.profile-container {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 100%;
    padding: 25px;
    background-size: cover;
    margin-bottom: 5px; /* Space for the bottom nav */
    overflow-y: auto; 
}

.profile-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    padding: 20px;
    background-color: #fff;
    border-bottom: 1px solid #ddd;
}

.icon-row {
    display: flex;
    justify-content: space-between;
    width: 100%;
    position: absolute;
    top: 10px;
    left: 0;
    padding: 0 20px;
    box-sizing: border-box;
}

.lock-icon,
.menu-icon {
    width: 30px;
    height: 30px;
    cursor: pointer;
}

.profile-header-content {
    margin-top: 30px;
    text-align: center;
}

.profile-pic {
    width: 150px;
    height: 150px;
    border-radius: 85px;
    margin-bottom: 10px;
}

h1 {
    font-size: 32px;
    margin: 5px 0;
}

.username {
    color: gray;
    margin-bottom: 5px;
}

.tagline {
    font-size: 18px;
    color: purple;
}

.stats {
    text-align: center;
    margin: 10px 0;
}

.stats p {
    font-size: 16px;
    color: #333;
}

.buttons {
    display: flex;
    justify-content: space-around;
    margin: 15px 0;
}

button {
    padding: 10px 15px;
    border: none;
    border-radius: 25px;
    background-color: #007bff;
    color: white;
    font-weight: bold;
    cursor: pointer;
    width: 120px;
}

button:hover {
    background-color: #0056b3;
}

.tabs {
    display: flex;
    justify-content: space-around;
    margin: 20px 0;
    border-bottom: 1px solid #ddd;
}

.tabs button {
    background: none;
    border: none;
    color: #007bff;
    font-weight: bold;
    cursor: pointer;
    font-size: 16px;
}

.tabs button:hover {
    color: #0056b3;
}

.completion-tasks {
    margin: 15px 0;
}

.task {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.task.completed p {
    color: #28a745;
    font-weight: bold;
}

.task button {
    background-color: #007bff;
}

.task.completed button {
    background-color: #28a745;
}

.task button:hover {
    background-color: #0056b3;
}

.task.completed button:hover {
    background-color: #218838;
}

.bottom-nav {
    display: flex;
    justify-content: space-around;
    bottom: 0;
    width: 100%;
    background-color: white;
    border-top: 1px solid #ddd;
    padding: 10px 0;
}

.bottom-nav a {
    color: black;
    text-decoration: none;
    text-align: center;
}

.bottom-nav a img {
    width: 30px;
    height: 30px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-container {
        padding: 15px; 
    }

    .buttons {
        flex-direction: column; 
    }

    button {
        width: 100%; 
    }
}

  </style>
</head>
<body>
<div class="profile-container">
    <header class="profile-header">
        <!-- Lock and Menu Icons -->
        <div class="icon-row">
            <img src="lock.jpg" class="lock-icon" alt="Lock Icon">
            <a href="log-out.php"><img src="menu.jpg" class="menu-icon" alt="Menu Icon"></a>
        </div>
        
        <!-- Profile Details -->
        <div class="profile-header-content">
            <img src="<?php echo $user['profile_pic'] ?: 'default-profile.png'; ?>" alt="Profile Picture" class="profile-pic">
            <h1><?php echo htmlspecialchars($user['full_name']); ?></h1>
            <p class="username">@<?php echo htmlspecialchars($user['username']); ?></p>
            <p class="tagline"><?php echo htmlspecialchars($user['tagline']); ?></p>
        </div>
    </header>
    <div class="stats">
      <p><strong><?php echo $user['followers_count']; ?></strong> Followers</p>
    </div>

    <div class="buttons">
      <button>Edit profile</button>
      <button>Share profile</button>
    </div>

    <nav class="tabs">
      <button>Threads</button>
      <button>Replies</button>
      <button>Reposts</button>
    </nav>

    <div class="completion-tasks">
      <div class="task">
        <p>Follow 3 profiles</p>
        <button>See profiles</button>
      </div>
      <div class="task completed">
        <p>Add profile photo</p>
        <button>Done</button>
      </div>
    </div>
  </div>

  <div class="bottom-nav">
        <a href="homepage.php"><img src="light-home.jpg" width="50" height="50"></a>
        <a href="#"><img src="light-search.jpg" width="50" height="50"></a>
        <a href="post.php"><img src="light-add.jpg" width="50" height="50"></a>
        <a href="#"><img src="light-heart.jpg" width="50" height="50"></a>
        <a href="profile.php"><img src="profile.jpg" width="50" height="50"></a>
    </div>
</body>
</html>

