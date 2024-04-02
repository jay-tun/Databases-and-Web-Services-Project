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

    $totalFeeQuery = "SELECT SUM(BM.Fee) as TotalFee
                     FROM Users U
                     JOIN BasicMembership BM ON U.UserID = BM.UserID
                     JOIN Location L ON U.UserID = L.UserID
                     WHERE L.City = ?";

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
                echo "<h2>Total Fee from $city:</h2>";
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