<?php
session_start();

if (isset($_SESSION['userId'])) {
    $_SESSION['errorMsg'] = 'Έχετε ήδη συνδεθεί.';
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['email']) && $_POST['password']) {
    require ('../conn.php');
    $inputEmail = $_POST['email'];
    $inputPass = $_POST['password'];

    // if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
    //     $_SESSION['errorMsg'] = 'Παρακαλώ εισάγετε ένα έγκυρο email';
    //     header("Location: ../login.php");
    //     exit();
    // }

    $query = "SELECT * FROM users WHERE (email = :input OR username = :input ) LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute([':input' => $inputEmail]);
    $userData = $stmt->fetch();

    if ($stmt->rowCount() > 0) {

        $dbUserId = $userData['id'];
        $dbEmail = $userData['email'];
        $dbPassword = $userData['password'];
        $dbUsername = $userData['username'];
        $dbPush = $userData['simplepush_key'];

        if (password_verify($inputPass, $dbPassword)) {

            $_SESSION['userId'] = $dbUserId;
            $_SESSION['userName'] = $dbUsername;
            $_SESSION['email'] = $dbEmail;
            $_SESSION['simplepush_key'] = $dbPush;
            header("Location: ../index.php");
            exit();

        } else {

            $_SESSION['errorMsg'] = "Λάθος στοιχεία εισόδου";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['errorMsg'] = "Λάθος στοιχεία εισόδου";
        header("Location: ../login.php");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}
