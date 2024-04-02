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
    // Retrieve user information from the form
    $Height = isset($_POST["Height"]) ? $_POST["Height"] : null;
    $Weight = isset($_POST["Weight"]) ? $_POST["Weight"] : null;
    $BodyMassIndex = isset($_POST["BodyMassIndex"]) ? $_POST["BodyMassIndex"] : null;
    $BodyFatPercentage = isset($_POST["BodyFatPercentage"]) ? $_POST["BodyFatPercentage"] : null;
    $UserID = isset($_POST["UserID"]) ? $_POST["UserID"] : null;

    // Check if required fields are not empty
    if (!empty($Height) && !empty($Weight) && !empty($BodyMassIndex) && !empty($BodyFatPercentage) && !empty($UserID)) {
        // Construct the SQL query to insert body measurement data
        $measurementQuery = "INSERT INTO BodyMeasurement (Height, Weight, BodyMassIndex, BodyFatPercentage, UserID) VALUES (?, ?, ?, ?, ?)";

        $measurementStmt = $mysqli->prepare($measurementQuery);

        if ($measurementStmt === false) {
            echo "Measurement Preparation Error: " . $mysqli->error;
        } else {
            $measurementStmt->bind_param("ddddi", $Height, $Weight, $BodyMassIndex, $BodyFatPercentage, $UserID);

            if ($measurementStmt->execute()) {
                echo "Successful Insertion!";
            } else {
                echo "Measurement Insertion Error: " . $measurementStmt->error;
            }

            $measurementStmt->close();
        }
    } else {
        echo "Measurement Insertion Error: Required fields cannot be empty.";
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>
    <body>
        <a href="maintenance.html"> Go Back to Maintenance Page </a>
    </body>
</html>
