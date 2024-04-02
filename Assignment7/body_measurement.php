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
    // Retrieve body measurement information from the form
    $Height = isset($_POST["Height"]) ? floatval($_POST["Height"]) : null;
    $Weight = isset($_POST["Weight"]) ? floatval($_POST["Weight"]) : null;
    $BodyMassIndex = isset($_POST["BodyMassIndex"]) ? floatval($_POST["BodyMassIndex"]) : null;
    $BodyFatPercentage = isset($_POST["BodyFatPercentage"]) ? floatval($_POST["BodyFatPercentage"]) : null;
    $UserID = isset($_POST["UserID"]) ? intval($_POST["UserID"]) : null;
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : null;

    // Check if required fields are not empty
    if (!empty($Height) && !empty($Weight) && !empty($BodyMassIndex) && !empty($BodyFatPercentage) && !empty($UserID) && !empty($Password)) {
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
                // UserID and Password are valid, proceed with body measurement insertion/update
                $passwordStmt->close();

                $bodyMeasurementQuery = "INSERT INTO BodyMeasurement (Height, Weight, BodyMassIndex, BodyFatPercentage, UserID) 
                                        VALUES (?, ?, ?, ?, ?)
                                        ON DUPLICATE KEY UPDATE 
                                            Height = VALUES(Height), 
                                            Weight = VALUES(Weight), 
                                            BodyMassIndex = VALUES(BodyMassIndex), 
                                            BodyFatPercentage = VALUES(BodyFatPercentage)";

                $bodyMeasurementStmt = $mysqli->prepare($bodyMeasurementQuery);

                if ($bodyMeasurementStmt === false) {
                    echo "Body Measurement Preparation Error: " . $mysqli->error;
                } else {
                    $bodyMeasurementStmt->bind_param("dddsi", $Height, $Weight, $BodyMassIndex, $BodyFatPercentage, $UserID);

                    if ($bodyMeasurementStmt->execute()) {
                        echo "Successful Insertion/Update!";
                    } else {
                        echo "Body Measurement Insertion/Update Error: " . $bodyMeasurementStmt->error;
                    }

                    $bodyMeasurementStmt->close();
                }
            } else {
                echo "Wrong UserID or Password.";
            }

            $passwordStmt->close();
        }
    } else {
        echo "Body Measurement Insertion/Update Error: Required fields cannot be empty.";
    }
}

$mysqli->close();
?>