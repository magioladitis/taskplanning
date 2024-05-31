<?php
session_start();
require '../conn.php';

if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $checkEmailQuery = "SELECT * FROM users WHERE email = :email AND id != :id";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->execute(['email' => $email, 'id' => $id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['errorMsg'] = "Το email χρησιμοποιείται ήδη από άλλον χρήστη.";
        header("Location: ../account.php");
        exit();
    }

    $updateQuery = "UPDATE users SET username = :username, surname = :surname, name = :name, email = :email WHERE id = :id";
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute(['username' => $username, 'surname' => $surname, 'name' => $name, 'email' => $email, 'id' => $id]);

    $_SESSION['successMsg'] = "Το προφίλ ενημερώθηκε";
    header("Location: ../account.php");
    exit();
} else {
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    header("Location: ../login.php");
    exit();
}

