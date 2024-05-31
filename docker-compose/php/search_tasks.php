<?php
session_start();
include 'conn.php';
include ('./partials/head.php');
include ('./partials/header.php');
include ('functions.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET['title']) || isset($_GET['status'])) && isset($_GET['list_id'])) {
    $title = $_GET['title'] ?? "";
    $status = $_GET['status'] ?? "";
    $list_id = $_GET['list_id'];

    $query = "
        SELECT 
            tasks.*,
            users.email AS creator_email
        FROM 
            tasks 
        JOIN 
            task_lists ON tasks.list_id = task_lists.list_id
        JOIN 
            users ON task_lists.user_id = users.id
        WHERE 
            1=1";

    $params = [];

    if (!empty($title)) {
        $query .= " AND tasks.title LIKE :title";
        $params[':title'] = "%" . $title . "%";
    }

    if (!empty($status)) {
        $query .= " AND tasks.status = :status";
        $params[':status'] = $status;
    }

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: show_lists.php");
    exit();
}

?>
<div class="container mt">
    <h1>Αποτελέσματα αναζήτησης</h1>
    <div class="mb-1">
        <a href="view_tasks.php?list_id=<?= $list_id ?>">Πίσω στις εργασίες</a>
    </div>
    <?php if (!empty($tasks)): ?>
        <?php foreach ($tasks as $task): ?>
            <?php include ('./partials/task.php'); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-danger">Δεν βρέθηκαν Αποτελέσματα.</p>
    <?php endif; ?>
</div>