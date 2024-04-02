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
    $age_type = isset($_POST["age_type"]) ? $_POST["age_type"] : '';
    $city = isset($_POST["city"]) ? $_POST["city"] : '';

    // Create a prepared statement to find the youngest or oldest person in the city
    $searchQuery = "SELECT U.UserID, U.Username, U.Email, U.Age, U.Name, U.Gender, U.Address
                    FROM Users U
                    JOIN Location L ON U.UserID = L.UserID
                    WHERE L.City = ?
                    ORDER BY U.Age " . ($age_type === 'oldest' ? 'DESC' : 'ASC') . "
                    LIMIT 1";

    $stmt = $mysqli->prepare($searchQuery);

    if ($stmt === false) {
        echo "Search Preparation Error: " . $mysqli->error;
    } else {
        $stmt->bind_param("s", $city);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<h2>" . ucfirst($age_type) . " Person in $city:</h2>";
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
                echo "No matching person found in $city.";
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