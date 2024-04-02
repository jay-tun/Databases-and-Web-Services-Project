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
    // Retrieve basic membership information from the form
    $Fee = isset($_POST["Fee"]) ? $_POST["Fee"] : null;
    $RenewalDate = isset($_POST["RenewalDate"]) ? $_POST["RenewalDate"] : null;
    $ContractLength = isset($_POST["ContractLength"]) ? $_POST["ContractLength"] : null;
    $GoodyBag = isset($_POST["GoodyBag"]) ? $_POST["GoodyBag"] : null;
    $UserID = isset($_POST["UserID"]) ? intval($_POST["UserID"]) : null;
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : null;

    // Check if required fields are not empty
    if (!empty($Fee) && !empty($RenewalDate) && !empty($ContractLength) && !empty($GoodyBag) && !empty($UserID) && !empty($Password)) {
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
                // UserID and Password are valid, proceed with basic membership insertion/update
                $passwordStmt->close();

                $membershipQuery = "INSERT INTO BasicMembership (Fee, RenewalDate, ContractLength, GoodyBag, UserID) 
                                    VALUES (?, ?, ?, ?, ?)
                                    ON DUPLICATE KEY UPDATE 
                                        Fee = VALUES(Fee), 
                                        RenewalDate = VALUES(RenewalDate), 
                                        ContractLength = VALUES(ContractLength), 
                                        GoodyBag = VALUES(GoodyBag)";

                $membershipStmt = $mysqli->prepare($membershipQuery);

                if ($membershipStmt === false) {
                    echo "Membership Preparation Error: " . $mysqli->error;
                } else {
                    $membershipStmt->bind_param("dsssi", $Fee, $RenewalDate, $ContractLength, $GoodyBag, $UserID);

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