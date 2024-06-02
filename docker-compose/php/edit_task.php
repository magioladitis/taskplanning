<?php
session_start();
include('./partials/head.php');
include('./partials/header.php');

// Sanitize the inputs
$task_id = filter_input(INPUT_GET, 'task_id', FILTER_SANITIZE_NUMBER_INT);
$list_id = filter_input(INPUT_GET, 'list_id', FILTER_SANITIZE_NUMBER_INT);

if (!$task_id || !$list_id) {
    header("Location: show_lists.php");
    exit;
}

try {
    require 'conn.php';

    // Prepare and execute the first statement
    $stmt = $conn->prepare("SELECT task_lists.user_id 
                            FROM task_lists
                            INNER JOIN tasks ON tasks.list_id = task_lists.list_id
                            WHERE tasks.task_id = :task_id AND tasks.list_id = :list_id");
    $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
    $stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result || $result['user_id'] != $_SESSION['userId']) {
        header("Location: show_lists.php?error=not_authorized");
        exit;
    }

    // Prepare and execute the second statement
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE task_id = :task_id");
    $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        header("Location: show_lists.php?error=task_not_found");
        exit;
    }

    // Fetch users
    $users = $conn->query("SELECT id, email, username FROM users WHERE email NOT LIKE '%-fake.com'")
                  ->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Log error message to a file or monitoring system
    error_log("Database error: " . $e->getMessage());
    header("Location: show_lists.php?error=db_error");
    exit;
}
?>

<div class="container container-sm mt">
    <form action="./submit-form/update_task.php" method="post" class="edit-task-form">
        <h1>Επεξεργασία εργασίας</h1>
        <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task_id); ?>">
        <input type="hidden" name="list_id" value="<?php echo htmlspecialchars($list_id); ?>">

        <label for="title">Τίτλος</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($task['title']); ?>">
        <span class="error-message" id="error-title"></span>

        <label for="description">Περιγραφή</label>
        <textarea id="description" name="description"><?php echo htmlspecialchars($task['description']); ?></textarea>
        <span class="error-message" id="error-description"></span>

        <label for="status">Κατάσταση</label>
        <select id="status" name="status">
            <option value="Pending" <?php echo $task['status'] == 'Pending' ? 'selected' : ''; ?>>Σε αναμονή</option>
            <option value="In Progress" <?php echo $task['status'] == 'In Progress' ? 'selected' : ''; ?>>Σε εξέλιξη</option>
            <option value="Completed" <?php echo $task['status'] == 'Completed' ? 'selected' : ''; ?>>Ολοκληρωμένη</option>
        </select>

        <label for="assignee">Ανάθεση εργασίας σε</label>
        <select id="assignee" name="assignee_user_id">
            <option value="">Επιλέξτε Χρήστη</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo htmlspecialchars($user['id']); ?>"><?php echo htmlspecialchars($user['username']); ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Ενημέρωση</button>
    </form>
</div>
<script src="js/validate-task.js"></script>
