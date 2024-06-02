<?php
session_start();
include('./partials/head.php');
include('./partials/header.php');

// Sanitize the list_id input
$list_id = filter_input(INPUT_GET, 'list_id', FILTER_SANITIZE_NUMBER_INT);

if (!$list_id) {
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    header("Location: login.php");
    exit;
}
?>
<div class="container container-sm mt">

    <form action="./submit-form/create_task.php?list_id=<?php echo htmlspecialchars($list_id); ?>" method="post" class="edit-task-form">
        <h1>Δημιουργία νέας εργασίας στη λίστα</h1>
        <input type="hidden" name="list_id" value="<?php echo htmlspecialchars($list_id); ?>">

        <label for="title">Τίτλος</label>
        <input type="text" id="title" name="title">
        <span class="error-message" id="error-title"></span>

        <label for="description">Περιγραφή</label>
        <textarea id="description" name="description"></textarea>
        <span class="error-message" id="error-description"></span>

        <label for="status">Κατάσταση</label>
        <select id="status" name="status">
            <option value="Pending">Σε αναμονή</option>
            <option value="In Progress">Σε εξέλιξη</option>
            <option value="Completed">Ολοκληρωμένη</option>
        </select>
        <button class="mb-1" type="submit">Προσθήκη</button>
    </form>
    <a href="view_tasks.php?list_id=<?php echo htmlspecialchars($list_id); ?>">Πίσω στη λίστα εργασιών</a>
</div>
<script src="js/validate-task.js"></script>
