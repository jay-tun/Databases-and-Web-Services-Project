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
    $UserID = isset($_POST["UserID"]) ? $_POST["UserID"] : null;

    // Check if required fields are not empty
    if (!empty($Fee) && !empty($RenewalDate) && !empty($ContractLength) && !empty($GoodyBag) && !empty($UserID)) {
        // Construct the SQL query to insert basic membership information
        $membershipQuery = "INSERT INTO BasicMembership (Fee, RenewalDate, ContractLength, GoodyBag, UserID) VALUES (?, ?, ?, ?, ?)";

        $membershipStmt = $mysqli->prepare($membershipQuery);

        if ($membershipStmt === false) {
            echo "Membership Preparation Error: " . $mysqli->error;
        } else {
            $membershipStmt->bind_param("dssi", $Fee, $RenewalDate, $ContractLength, $GoodyBag, $UserID);

            if ($membershipStmt->execute()) {
                echo "Successful Insertion!";
            } else {
                echo "Membership Insertion Error: " . $membershipStmt->error;
            }

            $membershipStmt->close();
        }
    } else {
        echo "Membership Insertion Error: Required fields cannot be empty.";
    }
}

$mysqli->close();
?>


<!DOCTYPE html>
<html>
    <body>
        <a href="maintenance.html"> Go Back to Maintenance Page </a>
    </body>
</html>
