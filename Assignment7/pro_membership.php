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
    // Retrieve pro membership information from the form
    $MassageChair = isset($_POST["MassageChair"]) ? boolval($_POST["MassageChair"]) : null;
    $OnlineCourse = isset($_POST["OnlineCourse"]) ? boolval($_POST["OnlineCourse"]) : null;
    $Drinks = isset($_POST["Drinks"]) ? boolval($_POST["Drinks"]) : null;
    $Shower = isset($_POST["Shower"]) ? boolval($_POST["Shower"]) : null;
    $AdditionalFeatures = isset($_POST["AdditionalFeatures"]) ? boolval($_POST["AdditionalFeatures"]) : null;
    $UserID = isset($_POST["UserID"]) ? intval($_POST["UserID"]) : null;
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : null;

    // Check if required fields are not empty
    if (!empty($MassageChair) && !empty($OnlineCourse) && !empty($Drinks) && !empty($Shower) && !empty($AdditionalFeatures) && !empty($UserID) && !empty($Password)) {
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
                // UserID and Password are valid, proceed with pro membership insertion/update
                $passwordStmt->close();

                $membershipQuery = "INSERT INTO ProMembership (MassageChair, OnlineCourse, Drinks, Shower, AdditionalFeatures, UserID) 
                                    VALUES (?, ?, ?, ?, ?, ?)
                                    ON DUPLICATE KEY UPDATE 
                                        MassageChair = VALUES(MassageChair), 
                                        OnlineCourse = VALUES(OnlineCourse), 
                                        Drinks = VALUES(Drinks), 
                                        Shower = VALUES(Shower), 
                                        AdditionalFeatures = VALUES(AdditionalFeatures)";

                $membershipStmt = $mysqli->prepare($membershipQuery);

                if ($membershipStmt === false) {
                    echo "Membership Preparation Error: " . $mysqli->error;
                } else {
                    $membershipStmt->bind_param("iiiiii", $MassageChair, $OnlineCourse, $Drinks, $Shower, $AdditionalFeatures, $UserID);

                    if ($membershipStmt->execute()) {
                        echo "Successful Insertion/Update!";
                    } else {
                        echo "Membership Insertion/Update Error: " . $membershipStmt->error;
                    }

                    $membershipStmt->close();
                }
            } else {
                echo "Wrong UserID or Password.";
            }

            $passwordStmt->close();
        }
    } else {
        echo "Membership Insertion/Update Error: Required fields cannot be empty.";
    }
}

$mysqli->close();
?>