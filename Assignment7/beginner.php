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
    // Retrieve beginner information from the form
    $SafetyTips = isset($_POST["SafetyTips"]) ? $_POST["SafetyTips"] : null;
    $CoachSupervision = isset($_POST["CoachSupervision"]) ? $_POST["CoachSupervision"] : null;
    $Instructions = isset($_POST["Instructions"]) ? $_POST["Instructions"] : null;
    $UserID = isset($_POST["UserID"]) ? intval($_POST["UserID"]) : null;
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : null;

    // Check if required fields are not empty
    if (!empty($SafetyTips) && !empty($CoachSupervision) && !empty($Instructions) && !empty($UserID) && !empty($Password)) {
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
                // UserID and Password are valid, proceed with beginner insertion/update
                $passwordStmt->close();

                $beginnerQuery = "INSERT INTO Beginner (SafetyTips, CoachSupervision, Instructions, UserID) 
                                  VALUES (?, ?, ?, ?)
                                  ON DUPLICATE KEY UPDATE 
                                      SafetyTips = VALUES(SafetyTips), 
                                      CoachSupervision = VALUES(CoachSupervision), 
                                      Instructions = VALUES(Instructions)";

                $beginnerStmt = $mysqli->prepare($beginnerQuery);

                if ($beginnerStmt === false) {
                    echo "Beginner Preparation Error: " . $mysqli->error;
                } else {
                    $beginnerStmt->bind_param("sssi", $SafetyTips, $CoachSupervision, $Instructions, $UserID);

                    if ($beginnerStmt->execute()) {
                        echo "Successful Insertion/Update!";
                    } else {
                        echo "Beginner Insertion/Update Error: " . $beginnerStmt->error;
                    }

                    $beginnerStmt->close();
                }
            } else {
                echo "Wrong UserID or Password.";
            }

            $passwordStmt->close();
        }
    } else {
        echo "Beginner Insertion/Update Error: Required fields cannot be empty.";
    }
}

$mysqli->close();
?>