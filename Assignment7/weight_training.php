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
    $SetNumber = isset($_POST["SetNumber"]) ? intval($_POST["SetNumber"]) : null;
    $MinReps = isset($_POST["MinReps"]) ? intval($_POST["MinReps"]) : null;
    $MaxReps = isset($_POST["MaxReps"]) ? intval($_POST["MaxReps"]) : null;
    $MinWeight = isset($_POST["MinWeight"]) ? floatval($_POST["MinWeight"]) : null;
    $MaxWeight = isset($_POST["MaxWeight"]) ? floatval($_POST["MaxWeight"]) : null;
    $UserID = isset($_POST["UserID"]) ? intval($_POST["UserID"]) : null;
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : null;

    // Check if required fields are not empty
    if (!empty($SetNumber) && !empty($MinReps) && !empty($MaxReps) && !empty($MinWeight) && !empty($MaxWeight) && !empty($UserID) && !empty($Password)) {
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
                // UserID and Password are valid, proceed with weight training insertion/update
                $passwordStmt->close();

                $weightTrainingQuery = "INSERT INTO WeightTraining (SetNumber, MinReps, MaxReps, MinWeight, MaxWeight, UserID) 
                                        VALUES (?, ?, ?, ?, ?, ?)
                                        ON DUPLICATE KEY UPDATE 
                                            SetNumber = VALUES(SetNumber), 
                                            MinReps = VALUES(MinReps), 
                                            MaxReps = VALUES(MaxReps), 
                                            MinWeight = VALUES(MinWeight), 
                                            MaxWeight = VALUES(MaxWeight)";

                $weightTrainingStmt = $mysqli->prepare($weightTrainingQuery);

                if ($weightTrainingStmt === false) {
                    echo "Weight Training Preparation Error: " . $mysqli->error;
                } else {
                    $weightTrainingStmt->bind_param("iiiiii", $SetNumber, $MinReps, $MaxReps, $MinWeight, $MaxWeight, $UserID);

                    if ($weightTrainingStmt->execute()) {
                        echo "Successful Insertion/Update!";
                    } else {
                        echo "Weight Training Insertion/Update Error: " . $weightTrainingStmt->error;
                    }

                    $weightTrainingStmt->close();
                }
            } else {
                echo "Wrong UserID or Password.";
            }

            $passwordStmt->close();
        }
    } else {
        echo "Weight Training Insertion/Update Error: Required fields cannot be empty.";
    }
}

$mysqli->close();
?>