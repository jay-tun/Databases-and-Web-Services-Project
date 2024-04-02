<?php
$server = 'localhost';
$username = 'ssulehri';
$mysql_password = 'gHiyGj';
$database = 'Group-29';

$mysqli = new mysqli($server, $username, $mysql_password, $database);

if ($mysqli->connect_error) {
    die("MySQL Connection failed: " . $mysqli->connect_error);
}

$userID = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Username = isset($_POST["Username"]) ? $_POST["Username"] : null;
    $Email = isset($_POST["Email"]) ? $_POST["Email"] : null;
    $Password = isset($_POST["Password"]) ? $_POST["Password"] : null;
    $Age = isset($_POST["Age"]) ? $_POST["Age"] : null;
    $Name = isset($_POST["Name"]) ? $_POST["Name"] : null;
    $Gender = isset($_POST["Gender"]) ? $_POST["Gender"] : null;
    $Address = isset($_POST["Address"]) ? $_POST["Address"] : null;



    if (!empty($Username) && !empty($Email) && !empty($Password) && !empty($Age) && !empty($Name) && !empty($Gender) && !empty($Address)) {
        $userQuery = "INSERT INTO Users (Username, Email, Password, Age, Name, Gender, Address) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $userStmt = $mysqli->prepare($userQuery);

        if ($userStmt === false) {
            echo "User Preparation Error: " . $mysqli->error;
        } else {
            $userStmt->bind_param("sssssss", $Username, $Email, $Password, $Age, $Name, $Gender, $Address);

            if ($userStmt->execute()) {
                $userID = $userStmt->insert_id;
                echo "Successful Insertion! Your UserID is: ". $userID;
            } else {
                echo "User Insertion Error: " . $userStmt->error;
            }

            $userStmt->close();
        }
    } else {
        echo "User Insertion Error: Required fields cannot be empty.";
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
