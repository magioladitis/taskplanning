<?php
session_start();
require '../conn.php';

if (!isset($_SESSION['userId'])) {
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    header("Location: login.php");
    exit();
}

$list_id = $_GET['id'] ?? null;
$user_id = $_SESSION['userId'];

if ($list_id) {
    try {
        $conn->beginTransaction();
        
        // GET LIST'S TASKS
        $stmt = $conn->prepare("SELECT task_id FROM tasks WHERE list_id = :list_id");
        $stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $stmt->execute();
        $tasks = $stmt->fetchAll();
        if ( !empty($tasks) ){
            // DELETE TASK ASSIGNMENTS
            foreach($tasks as $task){
                // DELETE TASKS
                $taskStmt = $conn->prepare("DELETE FROM task_assignments WHERE task_id = :task_id");
                $taskStmt->bindParam(':task_id', $task['task_id'], PDO::PARAM_INT);
                $taskStmt->execute();
            }
        }

        // DELETE TASKS
        $taskStmt = $conn->prepare("DELETE FROM tasks WHERE list_id = :list_id");
        $taskStmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $taskStmt->execute();

        // DELETE LIST
        $listStmt = $conn->prepare("DELETE FROM task_lists WHERE list_id = :list_id AND user_id = :user_id");
        $listStmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $listStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $listStmt->execute();

        if ($listStmt->rowCount() > 0) {
            $_SESSION['successMsg'] = "Η λίστα διαγράφηκε με επιτυχία.";
            $conn->commit();
            header("Location: ../show_lists.php");
            exit();
        } else {
            $_SESSION['errorMsg'] = "Κάτι πήγε στραβά, προσπαθήστε αργότερα.";
            $conn->rollBack();
            header("Location: ../show_lists.php");
            exit();
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        error_log("Database error: " . $e->getMessage());
        $_SESSION['errorMsg'] = "Σφάλμα κατά την επεξεργασία της αίτησης.";
        header("Location: ../show_lists.php");
        exit();
    }
} else {
    header("Location: ../show_lists.php");
}

