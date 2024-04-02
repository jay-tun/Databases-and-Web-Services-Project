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
    // Retrieve advanced information from the form
    $AdvancedTechnique = isset($_POST["AdvancedTechnique"]) ? $_POST["AdvancedTechnique"] : null;
    $Prerequisite = isset($_POST["Prerequisite"]) ? $_POST["Prerequisite"] : null;
    $PersonalRecordsChart = isset($_POST["PersonalRecordsChart"]) ? $_POST["PersonalRecordsChart"] : null;
    $TargetedMuscle = isset($_POST["TargetedMuscle"]) ? $_POST["TargetedMuscle"] : null;
    $UserID = isset($_POST["UserID"]) ? intval($_POST["UserID"]) : null;
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : null;

    // Check if required fields are not empty
    if (!empty($AdvancedTechnique) && !empty($Prerequisite) && !empty($PersonalRecordsChart) && !empty($TargetedMuscle) && !empty($UserID) && !empty($Password)) {
        // Check if the UserID and Password match
        $passwordQuery = "SELECT * FROM Users WHERE UserID = ? AND Password = ?";
        $passwordStmt = $mysqli->prepare($passwordQuery);

        if ($passwordStmt === false) {
            echo "Password Validation Preparation Error: " . $mysqli->error;
        } else {
            $passwordStmt->bind_param("is", $UserID, $Password);
            $passwordStmt->execute();
            $passwordStmt->store_result();

            if ($passwordStmt->num_rows > 0) {
                // UserID and Password are valid, proceed with advanced insertion/update
                $passwordStmt->close();

                $advancedQuery = "INSERT INTO Advanced (AdvancedTechnique, Prerequisite, PersonalRecordsChart, TargetedMuscle, UserID) 
                                  VALUES (?, ?, ?, ?, ?)
                                  ON DUPLICATE KEY UPDATE 
                                      AdvancedTechnique = VALUES(AdvancedTechnique), 
                                      Prerequisite = VALUES(Prerequisite), 
                                      PersonalRecordsChart = VALUES(PersonalRecordsChart), 
                                      TargetedMuscle = VALUES(TargetedMuscle)";

                $advancedStmt = $mysqli->prepare($advancedQuery);

                if ($advancedStmt === false) {
                    echo "Advanced Preparation Error: " . $mysqli->error;
                } else {
                    $advancedStmt->bind_param("ssssi", $AdvancedTechnique, $Prerequisite, $PersonalRecordsChart, $TargetedMuscle, $UserID);

                    if ($advancedStmt->execute()) {
                        echo "Successful Insertion/Update!";
                    } else {
                        echo "Advanced Insertion/Update Error: " . $advancedStmt->error;
                    }

                    $advancedStmt->close();
                }
            } else {
                echo "Wrong UserID or Password.";
            }

            $passwordStmt->close();
        }
    } else {
        echo "Advanced Insertion/Update Error: Required fields cannot be empty.";
    }
}

$mysqli->close();
?>