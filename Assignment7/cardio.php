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
    $AerobicClass = isset($_POST["AerobicClass"]) ? floatval($_POST["AerobicClass"]) : null;
    $CaloriesBurned = isset($_POST["CaloriesBurned"]) ? floatval($_POST["CaloriesBurned"]) : null;
    $RunningTrack = isset($_POST["RunningTrack"]) ? floatval($_POST["RunningTrack"]) : null;
    $CardioMachines = isset($_POST["CardioMachines"]) ? $_POST["CardioMachines"] : null;
    $UserID = isset($_POST["UserID"]) ? intval($_POST["UserID"]) : null;
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : null;

    // Check if required fields are not empty
    if (!empty($AerobicClass) && !empty($CaloriesBurned) && !empty($RunningTrack) && !empty($CardioMachines) && !empty($UserID) && !empty($Password)) {
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
                // UserID and Password are valid, proceed with cardio insertion/update
                $passwordStmt->close();

                $cardioQuery = "INSERT INTO Cardio (AerobicClass, CaloriesBurned, RunningTrack, CardioMachines, UserID) 
                                VALUES (?, ?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE 
                                    AerobicClass = VALUES(AerobicClass), 
                                    CaloriesBurned = VALUES(CaloriesBurned), 
                                    RunningTrack = VALUES(RunningTrack), 
                                    CardioMachines = VALUES(CardioMachines)";

                $cardioStmt = $mysqli->prepare($cardioQuery);

                if ($cardioStmt === false) {
                    echo "Cardio Preparation Error: " . $mysqli->error;
                } else {
                    $cardioStmt->bind_param("ddiss", $AerobicClass, $CaloriesBurned, $RunningTrack, $CardioMachines, $UserID);

                    if ($cardioStmt->execute()) {
                        echo "Successful Insertion/Update!";
                    } else {
                        echo "Cardio Insertion/Update Error: " . $cardioStmt->error;
                    }

                    $cardioStmt->close();
                }
            } else {
                echo "Wrong UserID or Password.";
            }

            $passwordStmt->close();
        }
    } else {
        echo "Cardio Insertion/Update Error: Required fields cannot be empty.";
    }
}

$mysqli->close();
?>