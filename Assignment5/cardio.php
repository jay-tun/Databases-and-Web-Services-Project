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
    // Retrieve cardio information from the form
    $AerobicClass = isset($_POST["AerobicClass"]) ? $_POST["AerobicClass"] : null;
    $CaloriesBurned = isset($_POST["CaloriesBurned"]) ? $_POST["CaloriesBurned"] : null;
    $RunningTrack = isset($_POST["RunningTrack"]) ? $_POST["RunningTrack"] : null;
    $CardioMachines = isset($_POST["CardioMachines"]) ? $_POST["CardioMachines"] : null;
    $UserID = isset($_POST["UserID"]) ? $_POST["UserID"] : null;

    // Check if required fields are not empty
    if (!empty($AerobicClass) && !empty($CaloriesBurned) && !empty($RunningTrack) && !empty($CardioMachines) && !empty($UserID)) {
        // Construct the SQL query to insert cardio information
        $cardioQuery = "INSERT INTO Cardio (AerobicClass, CaloriesBurned, RunningTrack, CardioMachines, UserID) VALUES (?, ?, ?, ?, ?)";

        $cardioStmt = $mysqli->prepare($cardioQuery);

        if ($cardioStmt === false) {
            echo "Cardio Preparation Error: " . $mysqli->error;
        } else {
            $cardioStmt->bind_param("dddsi", $AerobicClass, $CaloriesBurned, $RunningTrack, $CardioMachines, $UserID);

            if ($cardioStmt->execute()) {
                echo "Successful Insertion!";
            } else {
                echo "Cardio Insertion Error: " . $cardioStmt->error;
            }

            $cardioStmt->close();
        }
    } else {
        echo "Cardio Insertion Error: Required fields cannot be empty.";
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
