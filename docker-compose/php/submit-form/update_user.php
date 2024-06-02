<?php
session_start();
require '../conn.php';

if (!isset($_SESSION['userId'])) {
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // CSRF token validation
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['errorMsg'] = "Invalid CSRF token.";
        header("Location: ../account.php");
        exit();
    }

    // Email uniqueness check
    $checkEmailQuery = "SELECT * FROM users WHERE email = :email AND id != :id";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->execute(['email' => $email, 'id' => $id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['errorMsg'] = "Το email χρησιμοποιείται ήδη από άλλον χρήστη.";
        header("Location: ../account.php");
        exit();
    }

    // Update user details
    $updateQuery = "UPDATE users SET username = :username, surname = :surname, name = :name, email = :email WHERE id = :id";
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute(['username' => $username, 'surname' => $surname, 'name' => $name, 'email' => $email, 'id' => $id]);

    // Update password if provided
    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $updatePasswordQuery = "UPDATE users SET password = :password WHERE id = :id";
            $stmt = $conn->prepare($updatePasswordQuery);
            $stmt->execute(['password' => $hashed_password, 'id' => $id]);
        } else {
            $_SESSION['errorMsg'] = "Οι κωδικοί δεν ταιριάζουν.";
            header("Location: ../account.php");
            exit();
        }
    }

    $_SESSION['successMsg'] = "Το προφίλ ενημερώθηκε";
    header("Location: ../account.php");
    exit();
} else {
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    header("Location: ../login.php");
    exit();
}
?>
