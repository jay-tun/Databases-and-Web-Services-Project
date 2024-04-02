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
    // Retrieve pro plus membership information from the form
    $Spa = isset($_POST["Spa"]) ? boolval($_POST["Spa"]) : null;
    $StoneSona = isset($_POST["StoneSona"]) ? boolval($_POST["StoneSona"]) : null;
    $SteamSona = isset($_POST["SteamSona"]) ? boolval($_POST["SteamSona"]) : null;
    $PersonalTrainer = isset($_POST["PersonalTrainer"]) ? boolval($_POST["PersonalTrainer"]) : null;
    $UserID = isset($_POST["UserID"]) ? intval($_POST["UserID"]) : null;
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : null;

    // Check if required fields are not empty
    if (!empty($Spa) && !empty($StoneSona) && !empty($SteamSona) && !empty($PersonalTrainer) && !empty($UserID) && !empty($Password)) {
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
                // UserID and Password are valid, proceed with pro plus membership insertion/update
                $passwordStmt->close();

                $membershipQuery = "INSERT INTO ProPlusMembership (Spa, StoneSona, SteamSona, PersonalTrainer, UserID) 
                                    VALUES (?, ?, ?, ?, ?)
                                    ON DUPLICATE KEY UPDATE 
                                        Spa = VALUES(Spa), 
                                        StoneSona = VALUES(StoneSona), 
                                        SteamSona = VALUES(SteamSona), 
                                        PersonalTrainer = VALUES(PersonalTrainer)";

                $membershipStmt = $mysqli->prepare($membershipQuery);

                if ($membershipStmt === false) {
                    echo "Membership Preparation Error: " . $mysqli->error;
                } else {
                    $membershipStmt->bind_param("iiiii", $Spa, $StoneSona, $SteamSona, $PersonalTrainer, $UserID);

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