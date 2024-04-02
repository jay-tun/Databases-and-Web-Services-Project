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
    // Retrieve workout information from the form
    $ExerciseCategory = isset($_POST["ExerciseCategory"]) ? $_POST["ExerciseCategory"] : null;
    $ExercisePlan = isset($_POST["ExercisePlan"]) ? $_POST["ExercisePlan"] : null;
    $ExerciseLevel = isset($_POST["ExerciseLevel"]) ? $_POST["ExerciseLevel"] : null;
    $OnlineTutorial = isset($_POST["OnlineTutorial"]) ? $_POST["OnlineTutorial"] : null;
    $UserID = isset($_POST["UserID"]) ? intval($_POST["UserID"]) : null;
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : null;

    // Check if required fields are not empty
    if (!empty($ExerciseCategory) && !empty($ExercisePlan) && !empty($ExerciseLevel) && !empty($OnlineTutorial) && !empty($UserID) && !empty($Password)) {
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
                // UserID and Password are valid, proceed with workout insertion/update
                $passwordStmt->close();

                $workoutQuery = "INSERT INTO Workout (ExerciseCategory, ExercisePlan, ExerciseLevel, OnlineTutorial, UserID) 
                                 VALUES (?, ?, ?, ?, ?)
                                 ON DUPLICATE KEY UPDATE 
                                    ExerciseCategory = VALUES(ExerciseCategory), 
                                    ExercisePlan = VALUES(ExercisePlan), 
                                    ExerciseLevel = VALUES(ExerciseLevel), 
                                    OnlineTutorial = VALUES(OnlineTutorial)";

                $workoutStmt = $mysqli->prepare($workoutQuery);

                if ($workoutStmt === false) {
                    echo "Workout Preparation Error: " . $mysqli->error;
                } else {
                    $workoutStmt->bind_param("ssssi", $ExerciseCategory, $ExercisePlan, $ExerciseLevel, $OnlineTutorial, $UserID);

                    if ($workoutStmt->execute()) {
                        echo "Successful Insertion/Update!";
                    } else {
                        echo "Workout Insertion/Update Error: " . $workoutStmt->error;
                    }

                    $workoutStmt->close();
                }
            } else {
                echo "Wrong UserID or Password.";
            }

            $passwordStmt->close();
        }
    } else {
        echo "Workout Insertion/Update Error: Required fields cannot be empty.";
    }
}

$mysqli->close();
?>