<?php
session_start();

if (isset($_SESSION['userId'])) {
    $_SESSION['errorMsg'] = 'Παρακαλώ κάντε αποσύνδεση εαν θέλετε να συνδεθείται με άλλον λογαριασμό.';
    header("Location: ../register.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require('../conn.php');
    $username = $_POST['username'];
    $password = $_POST['password'];
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $simplepush_key = $_POST['simplepush_key'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $_SESSION['errorMsg'] = "Το Email υπαρχει ήδη.";
            header("Location: ../register.php");
            exit();
        } else {
            $stmt = $conn->prepare("SELECT * FROM users WHERE simplepush_key = ?");
            $stmt->execute([$simplepush_key]);
            $existingPush = $stmt->fetch();
            if ($existingPush) {
                $_SESSION['errorMsg'] = "Το simplepush key υπαρχει ήδη.";
                header("Location: ../register.php");
                exit();
            } else{
                if ( preg_match('/[^a-zA-Z0-9\s\p{Greek}]/u', $name) ){
                    $_SESSION['errorMsg'] = 'Το όνομα περιλαμβάνει μη επιτρεπτούς χαρακτήρες';
                    header("Location: ../register.php"); 
                    exit();
                }
    
                if ( preg_match('/[^a-zA-Z0-9\s\p{Greek}]/u', $surname) ){
                    $_SESSION['errorMsg'] = 'Το επώνυμο περιλαμβάνει μη επιτρεπτούς χαρακτήρες';
                    header("Location: ../register.php"); 
                    exit();
                }
    
                if ( preg_match('/[^a-zA-Z0-9\s\p{Greek}]/u', $username) ){
                    $_SESSION['errorMsg'] = 'Το username περιλαμβάνει μη επιτρεπτούς  χαρακτήρες';
                    header("Location: ../register.php"); 
                    exit();
                }
    
                if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ){
                    $_SESSION['errorMsg'] = 'Το email δεν είναι έγκυρο';
                    header("Location: ../register.php"); 
                    exit();
                }
                
                $sql = "INSERT INTO users (username, password, surname, name, email, simplepush_key) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$username, $hashed_password, $surname, $name, $email, $simplepush_key]);
    
                $_SESSION['successMsg'] = "H εγγραφή έγινε με επιτυχία.";
                header("Location: ../register.php");
                exit();
            }
        }
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
}
