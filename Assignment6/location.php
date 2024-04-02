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
    $UserID = isset($_POST["UserID"]) ? $_POST["UserID"] : null;

    // Check if required fields are not empty
    if (!empty($City) && !empty($State) && !empty($ZipCode) && !empty($UserID)) {
        // Construct the SQL query to insert location information
        $locationQuery = "INSERT INTO Location (City, State, ZipCode, UserID) VALUES (?, ?, ?, ?)";

        $locationStmt = $mysqli->prepare($locationQuery);

        if ($locationStmt === false) {
            echo "Location Preparation Error: " . $mysqli->error;
        } else {
            $locationStmt->bind_param("ssdi", $City, $State, $ZipCode, $UserID);

            if ($locationStmt->execute()) {
                echo "Successful Insertion!";
            } else {
                echo "Location Insertion Error: " . $locationStmt->error;
            }

            $locationStmt->close();
        }
    } else {
        echo "Location Insertion Error: Required fields cannot be empty.";
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
