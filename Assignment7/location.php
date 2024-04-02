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
    // Retrieve location information from the form
    $City = isset($_POST["City"]) ? $_POST["City"] : null;
    $State = isset($_POST["State"]) ? $_POST["State"] : null;
    $ZipCode = isset($_POST["ZipCode"]) ? $_POST["ZipCode"] : null;
    $UserID = isset($_POST["UserID"]) ? intval($_POST["UserID"]) : null;
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : null;

    // Check if required fields are not empty
    if (!empty($City) && !empty($State) && !empty($ZipCode) && !empty($UserID) && !empty($Password)) {
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
                // UserID and Password are valid, proceed with location insertion/update
                $passwordStmt->close();

                $locationQuery = "INSERT INTO Location (City, State, ZipCode, UserID) 
                                  VALUES (?, ?, ?, ?)
                                  ON DUPLICATE KEY UPDATE 
                                      City = VALUES(City), 
                                      State = VALUES(State), 
                                      ZipCode = VALUES(ZipCode)";

                $locationStmt = $mysqli->prepare($locationQuery);

                if ($locationStmt === false) {
                    echo "Location Preparation Error: " . $mysqli->error;
                } else {
                    $locationStmt->bind_param("sssi", $City, $State, $ZipCode, $UserID);

                    if ($locationStmt->execute()) {
                        echo "Successful Insertion/Update!";
                    } else {
                        echo "Location Insertion/Update Error: " . $locationStmt->error;
                    }

                    $locationStmt->close();
                }
            } else {
                echo "Wrong UserID or Password.";
            }

            $passwordStmt->close();
        }
    } else {
        echo "Location Insertion/Update Error: Required fields cannot be empty.";
    }
}

$mysqli->close();
?>