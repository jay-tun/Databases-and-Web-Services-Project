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
    $city = isset($_POST["city"]) ? $_POST["city"] : '';
    $gender = isset($_POST["gender"]) ? $_POST["gender"] : '';

    $averageQuery = "SELECT AVG(BM.Weight) as AverageWeight
                     FROM Users U
                     JOIN BodyMeasurement BM ON U.UserID = BM.UserID
                     JOIN Location L ON U.UserID = L.UserID
                     WHERE L.City = ? AND U.Gender = ?";

    $stmt = $mysqli->prepare($averageQuery);

    if ($stmt === false) {
        echo "Average Calculation Preparation Error: " . $mysqli->error;
    } else {
        $stmt->bind_param("ss", $city, $gender);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $averageWeight = $row["AverageWeight"];

            if (!empty($averageWeight)) {
                echo "<h2>Average Weight in $city for $gender:</h2>";
                echo "Average Weight: " . number_format($averageWeight, 2) . " kg";
            } else {
                echo "No data found for the selected criteria.";
            }
        } else {
            echo "Average Calculation Error: " . $stmt->error;
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