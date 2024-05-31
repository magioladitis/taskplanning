<div class="task">
    <div class="d-flex align-items-center justify-content-between">
        <h2><?php echo htmlspecialchars($task['title']); ?></h2>
        <?php if ($_SESSION['email'] == $task['creator_email']): ?>
            <a href="edit_task.php?task_id=<?= htmlspecialchars($task['task_id']) ?>&list_id=<?= htmlspecialchars($task['list_id']) ?>">Επεξεργασία</a>
        <?php endif; ?>
    </div>
    <p class="mb-1"><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
    <div>
        <span>Κατάσταση :</span>
        <span class="status"><?php echo translateToGreek(htmlspecialchars($task['status'])); ?></span>
    </div>
    <div>
        <p>
            <span>Δημιουργήθηκε στις : </span>
            <b><?php echo htmlspecialchars($task['created_at']); ?></b>
        </p>
    </div>
    <div class="d-flex align-items-center justify-content-between">
        <?php if (!empty($task['assignees'])): ?>
            <div>
                <p class="mt-1 mb-1">Έχει ανατεθεί σε : </p>
                <ul>
                    <?php foreach ($task['assignees'] as $assignee): ?>
                        <li><b><?= htmlspecialchars($assignee) ?></b></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <p class="mt-1">Η εργασία δεν έχει ανατεθεί ακόμη</p>
        <?php endif; ?>
        <?php if ($_SESSION['email'] == $task['creator_email']): ?>
            <a class="text-danger" href="javascript:void(0);" onclick="confirmDeletion(<?= htmlspecialchars($task['task_id']) ?>, <?= htmlspecialchars($task['list_id']) ?>)">
                <i class="fas fa-trash-alt"></i> <!-- Font Awesome trash icon --> <!-- Διαγραφή εργασίας -->
            </a>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmDeletion(taskId, listId) {
        if (confirm('Είστε βέβαιοι ότι θέλετε να διαγράψετε αυτή την εργασία;')) {
            window.location.href = './submit-form/delete_task.php?task_id=' + encodeURIComponent(taskId) + '&list_id=' + encodeURIComponent(listId);
        }
    }
</script>
