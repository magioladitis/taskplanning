<?php
require '../conn.php';

session_start();

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    $randomUsername = generateRandomString();
    $randomSurname = generateRandomString();
    $randomName = generateRandomString();
    $randomEmail = generateRandomString(5) . '@' . generateRandomString(3) . '-fake.com';

    $query = "UPDATE users SET username = :username, surname = :surname, password = '-', name = :name, email = :email WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['username' => $randomUsername, 'surname' => $randomSurname, 'name' => $randomName, 'email' => $randomEmail, 'id' => $userId]);

    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();

    header("Location: ../login.php");
    header("Location: ../login.php?msg=" . urlencode("Ο λογαριασμός έχει απενεργοποιηθεί"));
    exit();
} else {
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    header("Location: ../login.php");
    exit();
}
