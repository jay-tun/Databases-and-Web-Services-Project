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
    $genders = isset($_POST["gender"]) ? $_POST["gender"] : [];

    $gender_conditions = "";
    if (!empty($genders)) {
        $gender_conditions = "AND (";
        foreach ($genders as $gender) {
            $gender_conditions .= "Gender = '$gender' OR ";
        }
        $gender_conditions = rtrim($gender_conditions, " OR ");
        $gender_conditions .= ")";
    }

    // Create a prepared statement to calculate the average weight
    $averageQuery = "SELECT AVG(Weight) as AverageWeight FROM BodyMeasurement 
                     WHERE UserID IN (
                        SELECT UserID FROM Users 
                        WHERE City = ? $gender_conditions
                     )";

    $stmt = $mysqli->prepare($averageQuery);

    if ($stmt === false) {
        echo "Average Calculation Preparation Error: " . $mysqli->error;
    } else {
        $stmt->bind_param("s", $city);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $averageWeight = $row["AverageWeight"];

            if (!empty($averageWeight)) {
                echo "<h2>Average Weight in $city for selected gender(s):</h2>";
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