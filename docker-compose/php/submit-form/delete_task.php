<?php
session_start();
require '../conn.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    exit();
}

$task_id = $_GET['task_id'] ?? null;
$list_id = $_GET['list_id'] ?? null;

if ($task_id && $list_id) {
    try {

        // DELETE TASK ASSIGNMENTS
        $stmt = $conn->prepare("DELETE FROM task_assignments WHERE task_id = :task_id");
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = :task_id AND list_id = :list_id");
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../view_tasks.php?list_id=" . $list_id);
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: ../login.php");
    exit();
}

