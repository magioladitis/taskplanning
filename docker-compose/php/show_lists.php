<?php
session_start();
include('./partials/head.php');
include('./partials/header.php');

try {
    require 'conn.php';
    $userId = $_SESSION['userId'] ?? null;

    if (!$userId) {
        $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
        header("Location: login.php");
        exit;
    }

    $stmt = $conn->prepare("
        SELECT DISTINCT 
            tl.list_id, 
            tl.title, 
            tl.created_at, 
            tl.user_id,
            u.username AS creator_username
        FROM 
            task_lists tl
        JOIN 
            users u ON tl.user_id = u.id
        LEFT JOIN 
            tasks t ON tl.list_id = t.list_id
        LEFT JOIN 
            task_assignments ta ON t.task_id = ta.task_id
        WHERE 
            tl.user_id = :user_id OR ta.assignee_user_id = :user_id
        ORDER BY 
            tl.created_at DESC
    ");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="container mt">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Οι λίστες εργασιών μου</h1>
        <?php if (!empty($lists)): ?>
            <form action="export_xml.php" method="post">
                <button type="submit" name="export_xml">Εξαγωγη σε XML</button>
            </form>
        <?php endif; ?>
    </div>
    <div class="user-task-lists">
        <?php if (!empty($lists)): ?>
            <?php foreach ($lists as $list): ?>
                <div class="card">
                    <h2><?php echo htmlspecialchars($list['title']); ?></h2>
                    <a href="view_tasks.php?list_id=<?php echo $list['list_id']; ?>">Προβολή εργασιών</a>
                    <?php if ($userId == $list['user_id']): ?>
                        <a class="text-danger" href="javascript:void(0);"
                            onclick="confirmDeletion('<?php echo htmlspecialchars($list['list_id']); ?>', '<?php echo htmlspecialchars($list['title']); ?>')">
                            <i class="fas fa-trash-alt"></i> <!-- Font Awesome trash icon --> <!-- Διαγραφή λίστας -->
                        </a>
                    <?php endif; ?>
                    <p class="mt-1 font-12">Αναρτήθηκε στις : <br><?php echo htmlspecialchars($list['created_at']); ?> από τον χρήστη <?php echo htmlspecialchars($list['creator_username']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Δεν βρέθηκαν λίστες εργασιών. <a href="create_list.php">Δημιουργήστε μια τώρα</a>.</p>
        <?php endif; ?>

        <script>
            function confirmDeletion(listId, title) {
                if (confirm(`Είστε βέβαιοι ότι θέλετε να διαγράψετε τη λίστα εργασιών "${title}" ;`)) {
                    window.location.href = './submit-form/delete_list.php?id=' + listId;
                }
            }
        </script>

        <?php if (isset($_SESSION['errorMsg'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['errorMsg']; unset($_SESSION['errorMsg']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['successMsg'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['successMsg']; unset($_SESSION['successMsg']); ?></div>
        <?php endif; ?>
    </div>
</div>

<?php include('./partials/footer.php'); ?>
