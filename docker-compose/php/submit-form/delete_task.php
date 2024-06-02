<?php
session_start();
require '../conn.php';

if (!isset($_SESSION['userId'])) {
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    header("Location: login.php");
    exit();
}

$task_id = filter_input(INPUT_GET, 'task_id', FILTER_SANITIZE_NUMBER_INT);
$list_id = filter_input(INPUT_GET, 'list_id', FILTER_SANITIZE_NUMBER_INT);

if ($task_id && $list_id) {
    try {
        $conn->beginTransaction();

        // DELETE TASK ASSIGNMENTS
        $stmt = $conn->prepare("DELETE FROM task_assignments WHERE task_id = :task_id");
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->execute();

        // DELETE TASK
        $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = :task_id AND list_id = :list_id");
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->execute();

        $conn->commit();

        header("Location: ../view_tasks.php?list_id=" . $list_id);
        exit();
    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['errorMsg'] = "Σφάλμα κατά τη διαγραφή της εργασίας: " . $e->getMessage();
        header("Location: ../view_tasks.php?list_id=" . $list_id);
        exit();
    }
} else {
    $_SESSION['errorMsg'] = "Μη έγκυρη εργασία ή λίστα.";
    header("Location: ../login.php");
    exit();
}
?>
