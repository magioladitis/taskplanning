<?php
$servername = "di_inter_tech_mysql";
$dbuser = "webuser";
$dbpass = 'webpass';
try {
    $conn = new PDO("mysql:host=$servername;dbname=di_internet_technologies_project;port=3306;", $dbuser, $dbpass);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
