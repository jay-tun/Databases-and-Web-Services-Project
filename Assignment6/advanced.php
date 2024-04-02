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
    // Retrieve advanced workout information from the form
    $AdvancedTechnique = isset($_POST["AdvancedTechnique"]) ? $_POST["AdvancedTechnique"] : null;
    $Prerequisite = isset($_POST["Prerequisite"]) ? $_POST["Prerequisite"] : null;
    $PersonalRecordsChart = isset($_POST["PersonalRecordsChart"]) ? $_POST["PersonalRecordsChart"] : null;
    $TargetedMuscle = isset($_POST["TargetedMuscle"]) ? $_POST["TargetedMuscle"] : null;
    $UserID = isset($_POST["UserID"]) ? $_POST["UserID"] : null;

    // Check if required fields are not empty
    if (!empty($AdvancedTechnique) && !empty($Prerequisite) && !empty($PersonalRecordsChart) && !empty($TargetedMuscle) && !empty($UserID)) {
        // Construct the SQL query to insert advanced workout information
        $advancedQuery = "INSERT INTO Advanced (AdvancedTechnique, Prerequisite, PersonalRecordsChart, TargetedMuscle, UserID) VALUES (?, ?, ?, ?, ?)";

        $advancedStmt = $mysqli->prepare($advancedQuery);

        if ($advancedStmt === false) {
            echo "Advanced Workout Preparation Error: " . $mysqli->error;
        } else {
            $advancedStmt->bind_param("dddsi", $AdvancedTechnique, $Prerequisite, $PersonalRecordsChart, $TargetedMuscle, $UserID);

            if ($advancedStmt->execute()) {
                echo "Successful Insertion!";
            } else {
                echo "Advanced Workout Insertion Error: " . $advancedStmt->error;
            }

            $advancedStmt->close();
        }
    } else {
        echo "Advanced Workout Insertion Error: Required fields cannot be empty.";
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