<?php
$server = 'localhost';
$username = 'ssulehri';
$mysql_password = 'gHiyGj';
$database = 'Group-29';

$mysqli = new mysqli($server, $username, $mysql_password, $database);

if ($mysqli->connect_error) {
    die("MySQL Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = isset($_POST["search_term"]) ? $_POST["search_term"] : '';

    $searchQuery = "SELECT * FROM Users 
                    WHERE Username LIKE ? 
                    OR Email LIKE ? 
                    OR Name LIKE ? 
                    OR Address LIKE ?";

    $search_term = "%$search_term%"; 
    $stmt = $mysqli->prepare($searchQuery);

    if ($stmt === false) {
        echo "Search Preparation Error: " . $mysqli->error;
    } else {
        $stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<h2>Search Results:</h2>";
                echo "<table border='1'>";
                echo "<tr><th>User ID</th><th>Username</th><th>Email</th><th>Age</th><th>Name</th><th>Gender</th><th>Address</th></tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["UserID"] . "</td>";
                    echo "<td>" . $row["Username"] . "</td>";
                    echo "<td>" . $row["Email"] . "</td>";
                    echo "<td>" . $row["Age"] . "</td>";
                    echo "<td>" . $row["Name"] . "</td>";
                    echo "<td>" . $row["Gender"] . "</td>";
                    echo "<td>" . $row["Address"] . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "No matching users found.";
            }
        } else {
            echo "Search Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>
    <body>
        <a href="web.html"> Go Back to Home Page </a>
    </body>
</html>