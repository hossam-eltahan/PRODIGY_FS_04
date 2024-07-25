<?php /*
// user_list.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chat_application";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the list of users
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
$current_user = $_SESSION["username"];

$sql = "SELECT username FROM users WHERE username != '$current_user'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .user-list-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-list-container h2 {
            margin-bottom: 20px;
        }

        .user-list-container ul {
            list-style-type: none;
            padding: 0;
        }

        .user-list-container li {
            margin: 10px 0;
        }

        .user-list-container a {
            text-decoration: none;
            color: #007bff;
            font-size: 18px;
        }

        .user-list-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="user-list-container">
        <h2>Registered Users</h2>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li><a href='chat.php?with={$row['username']}'>{$row['username']}</a></li>";
                }
            } else {
                echo "<li>No users found.</li>";
            }
            ?>
        </ul>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>

<?php
$conn->close();*/
?>
