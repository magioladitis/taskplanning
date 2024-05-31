<?php
session_start();
include ('./partials/head.php');
include ('./partials/header.php');
include ('functions.php');

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    exit;
}

$list_id = $_GET['list_id'] ?? null;

if (!$list_id) {
    header("Location: show_lists.php");
    exit;
}
try {
    require 'conn.php';
    $stmt = $conn->prepare("SELECT 
    tasks.*, 
    creator.email AS creator_email,
    assignee.email AS assignee_email,
    assignee.username AS assignee_username
    FROM 
    tasks
    LEFT JOIN 
    task_assignments ON tasks.task_id = task_assignments.task_id
    LEFT JOIN 
    users AS assignee ON task_assignments.assignee_user_id = assignee.id
    JOIN 
    task_lists ON tasks.list_id = task_lists.list_id
    JOIN 
    users AS creator ON task_lists.user_id = creator.id
    WHERE 
    tasks.list_id = :list_id
    ORDER BY 
    tasks.created_at DESC");
    $stmt->bindParam(':list_id', $list_id, PDO::PARAM_INT);
    $stmt->execute();
    $rawTasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tasks = [];
    foreach ($rawTasks as $task) {
        $taskId = $task['task_id'];
        if (!isset($tasks[$taskId])) {
            $tasks[$taskId] = $task;
            $tasks[$taskId]['assignees'] = [];
        }
        if (!empty($task['assignee_email'])) {
            $tasks[$taskId]['assignees'][] = $task['assignee_username'].' ('.$task['assignee_email'].')';
        }
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

?>

<div class="container mt">
    <h3>Αναζήτηση Εργασιών</h3>
    <form class="search-form" action="search_tasks.php" method="get">
        <div>
            <label for="title">Τίτλος</label>
            <br>
            <input type="text" id="title" name="title">
        </div>
        <div>
            <label for="status">Κατάσταση</label>
            <br>
            <select id="status" name="status">
                <option value="">Όλες</option>
                <option value="Pending">Σε αναμονή</option>
                <option value="In Progress">Σε εξέλιξη</option>
                <option value="Completed">Ολοκληρωμένη</option>
            </select>
            <input type="hidden" value="<?= $list_id ?>" name="list_id">
        </div>
        <button type="submit">Αναζήτηση</button>
    </form>
    <div class="d-flex align-items-center justify-content-between">
        <h1>Εργασίες στη λίστα</h1>
        <?php if (!empty($tasks)): ?>
            <a href="create_task.php?list_id=<?php echo $list_id; ?>">Προσθήκη Εργασιών στη λίστα</a>
        <?php endif; ?>
    </div>
    <div class="mb-1">
        <a href="show_lists.php">Πίσω στις λιστες εργασίων</a>
    </div>
    <?php if (!empty($tasks)): ?>
        <?php foreach ($tasks as $task): ?>

        <?php include ('./partials/task.php'); ?>

        <?php endforeach; ?>
    <?php else: ?>
        <p>Δεν βρέθηκαν εργασίες. <br><a href="create_task.php?list_id=<?php echo $list_id; ?>">Προσθήκη Εργασιών στη
                λίστα</a>.</p>
    <?php endif; ?>
</div>
<script>
    function confirmDeletion(taskId, listId) {
        if (confirm('Είστε βέβαιοι ότι θέλετε να διαγράψετε αυτήν την εργασία;')) {
            window.location.href = './submit-form/delete_task.php?task_id=' + taskId + '&list_id=' + listId;
        }
    }
</script>