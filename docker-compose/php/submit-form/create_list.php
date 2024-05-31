<?php
session_start();
require '../conn.php';

try {

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['title'])) {
        $title = $_POST['title'];
        $user_id = $_SESSION['userId'];

        $stmt = $conn->prepare("INSERT INTO task_lists (title, user_id) VALUES (:title, :user_id)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        $_SESSION['successMsg'] = "Η λίστα δημιουργήθηκε με επιτυχία!";
        header("Location: ../create_list.php");
        exit();
    } else {
        header("Location: ../login.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
