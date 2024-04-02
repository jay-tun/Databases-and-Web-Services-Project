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
    // Retrieve ProPlus membership information from the form
    $Spa = isset($_POST["Spa"]) ? $_POST["Spa"] : null;
    $StoneSona = isset($_POST["StoneSona"]) ? $_POST["StoneSona"] : null;
    $SteamSona = isset($_POST["SteamSona"]) ? $_POST["SteamSona"] : null;
    $PersonalTrainer = isset($_POST["PersonalTrainer"]) ? $_POST["PersonalTrainer"] : null;
    $UserID = isset($_POST["UserID"]) ? $_POST["UserID"] : null;

    // Check if required fields are not empty
    if (!empty($Spa) && !empty($StoneSona) && !empty($SteamSona) && !empty($PersonalTrainer) && !empty($UserID)) {
        // Construct the SQL query to insert ProPlus membership information
        $membershipQuery = "INSERT INTO ProPlusMembership (Spa, StoneSona, SteamSona, PersonalTrainer, UserID) VALUES (?, ?, ?, ?, ?)";

        $membershipStmt = $mysqli->prepare($membershipQuery);

        if ($membershipStmt === false) {
            echo "Membership Preparation Error: " . $mysqli->error;
        } else {
            $membershipStmt->bind_param("bbbbi", $Spa, $StoneSona, $SteamSona, $PersonalTrainer, $UserID);

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