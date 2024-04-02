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
    $city = isset($_POST["city"]) ? $_POST["city"] : '';

    // Create a prepared statement to calculate the total fee
    $totalFeeQuery = "SELECT SUM(Fee) as TotalFee FROM BasicMembership 
                     WHERE UserID IN (
                        SELECT UserID FROM Users 
                        WHERE City = ?
                     )";

    $stmt = $mysqli->prepare($totalFeeQuery);

    if ($stmt === false) {
        echo "Total Fee Calculation Preparation Error: " . $mysqli->error;
    } else {
        $stmt->bind_param("s", $city);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $totalFee = $row["TotalFee"];

            if (!empty($totalFee)) {
                echo "<h2>Total Fee from users in $city:</h2>";
                echo "Total Fee: $" . number_format($totalFee, 2);
            } else {
                echo "No data found for the selected city.";
            }
        } else {
            echo "Total Fee Calculation Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$mysqli->close();
?>


<!DOCTYPE html>
<html>
    <body>
        <a href="web.html"> Go Back to Home Page </a>
    </body>
</html>