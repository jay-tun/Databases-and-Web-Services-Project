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
    // Retrieve weight training information from the form
    $SetNumber = isset($_POST["SetNumber"]) ? $_POST["SetNumber"] : null;
    $MinReps = isset($_POST["MinReps"]) ? $_POST["MinReps"] : null;
    $MaxReps = isset($_POST["MaxReps"]) ? $_POST["MaxReps"] : null;
    $MinWeight = isset($_POST["MinWeight"]) ? $_POST["MinWeight"] : null;
    $MaxWeight = isset($_POST["MaxWeight"]) ? $_POST["MaxWeight"] : null;
    $UserID = isset($_POST["UserID"]) ? $_POST["UserID"] : null;

    // Check if required fields are not empty
    if (!empty($SetNumber) && !empty($MinReps) && !empty($MaxReps) && !empty($MinWeight) && !empty($MaxWeight) && !empty($UserID)) {
        // Construct the SQL query to insert weight training information
        $trainingQuery = "INSERT INTO WeightTraining (SetNumber, MinReps, MaxReps, MinWeight, MaxWeight, UserID) VALUES (?, ?, ?, ?, ?, ?)";

        $trainingStmt = $mysqli->prepare($trainingQuery);

        if ($trainingStmt === false) {
            echo "Weight Training Preparation Error: " . $mysqli->error;
        } else {
            $trainingStmt->bind_param("iidddi", $SetNumber, $MinReps, $MaxReps, $MinWeight, $MaxWeight, $UserID);

            if ($trainingStmt->execute()) {
                echo "Successful Insertion!";
            } else {
                echo "Weight Training Insertion Error: " . $trainingStmt->error;
            }

            $trainingStmt->close();
        }
    } else {
        echo "Weight Training Insertion Error: Required fields cannot be empty.";
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
