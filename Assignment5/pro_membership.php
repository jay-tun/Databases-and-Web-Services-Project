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
    $MassageChair = isset($_POST["MassageChair"]) ? $_POST["MassageChair"] : null;
    $OnlineCourse = isset($_POST["OnlineCourse"]) ? $_POST["OnlineCourse"] : null;
    $Drinks = isset($_POST["Drinks"]) ? $_POST["Drinks"] : null;
    $Shower = isset($_POST["Shower"]) ? $_POST["Shower"] : null;
    $AdditionalFeatures = isset($_POST["AdditionalFeatures"]) ? $_POST["AdditionalFeatures"] : null;
    $UserID = isset($_POST["UserID"]) ? $_POST["UserID"] : null;

    // Check if required fields are not empty
    if (!empty($MassageChair) && !empty($OnlineCourse) && !empty($Drinks) && !empty($Shower) && !empty($AdditionalFeatures) & !empty($UserID)) {
        // Construct the SQL query to insert pro membership information
        $membershipQuery = "INSERT INTO ProMembership (MassageChair, OnlineCourse, Drinks, Shower, AdditionalFeatures, UserID) VALUES (?, ?, ?, ?, ?, ?)";

        $membershipStmt = $mysqli->prepare($membershipQuery);

        if ($membershipStmt === false) {
            echo "Membership Preparation Error: " . $mysqli->error;
        } else {
            $membershipStmt->bind_param("bbbbbi", $MassageChair, $OnlineCourse, $Drinks, $Shower, $AdditionalFeatures, $UserID);

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
