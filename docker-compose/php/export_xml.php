<?php
session_start();
require ('conn.php');

$loggedInUserId = $_SESSION['userId'] ?? null;

if (!$loggedInUserId) {
    header("Location: login.php");
    $_SESSION['errorMsg'] = "Παρακαλώ πραγματοποιηστε είσοδο.";
    exit();
}

$sql = " SELECT 
tl.list_id,
tl.title AS list_title,
t.task_id,
t.title AS task_title,
t.description,
t.status,
creator.username AS creator_username,
GROUP_CONCAT(DISTINCT u.username SEPARATOR ', ') AS assigned_users
FROM 
task_lists tl
LEFT JOIN 
tasks t ON tl.list_id = t.list_id
LEFT JOIN 
task_assignments ta ON t.task_id = ta.task_id
LEFT JOIN 
users u ON ta.assignee_user_id = u.id
JOIN 
users creator ON tl.user_id = creator.id
WHERE 
tl.user_id = :user_id OR ta.assignee_user_id = :user_id
GROUP BY 
t.task_id,
tl.list_id,
tl.title,
t.title,
t.description,
t.status,
creator.username
ORDER BY 
tl.list_id, t.task_id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $loggedInUserId, PDO::PARAM_INT);
$stmt->execute();
$taskLists = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dom = new DOMDocument();
$dom->formatOutput = true;
$root = $dom->createElement('taskLists');
$dom->appendChild($root);

$currentListId = null;
$listElement = null;

foreach ($taskLists as $task) {
    if ($currentListId !== $task['list_id']) {
        $listElement = $dom->createElement('taskList');
        $root->appendChild($listElement);
        $listElement->appendChild($dom->createElement('list_id', htmlspecialchars($task['list_id'])));
        $listElement->appendChild($dom->createElement('list_title', htmlspecialchars($task['list_title'])));
        $creatorUsername = $task['creator_username'] ?? 'No username given';
        $listElement->appendChild($dom->createElement('creator_username', htmlspecialchars($creatorUsername)));
        $currentListId = $task['list_id'];
    }

    if (!empty($task['task_id'])) {
        $taskElement = $dom->createElement('task');
        $listElement->appendChild($taskElement);
        $taskElement->appendChild($dom->createElement('task_id', htmlspecialchars($task['task_id'])));
        $taskElement->appendChild($dom->createElement('task_title', htmlspecialchars($task['task_title'])));
        $taskElement->appendChild($dom->createElement('description', htmlspecialchars($task['description'])));
        $taskElement->appendChild($dom->createElement('status', htmlspecialchars($task['status'])));
        $assignedUsers = !empty($task['assigned_users']) ? $task['assigned_users'] : 'No users assigned';
        $taskElement->appendChild($dom->createElement('assigned_users', htmlspecialchars($assignedUsers)));
    }
}

header('Content-Type: text/xml');
header('Content-Disposition: attachment; filename="task_lists.xml"');
echo $dom->saveXML();

