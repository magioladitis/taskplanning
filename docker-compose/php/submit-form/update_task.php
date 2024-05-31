<?php
session_start();
require '../conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $list_id = $_POST['list_id'];
    $status = $_POST['status'];
    $assignee_user_id = $_POST['assignee_user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    try {
        $stmt = $conn->prepare("UPDATE tasks SET title = :title, description = :description, status = :status WHERE task_id = :task_id");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':task_id', $task_id);
        $stmt->execute();

        // Update the assignee if changed
        if (!empty($assignee_user_id)) {
            $stmt = $conn->prepare("REPLACE INTO task_assignments (task_id, assignee_user_id) VALUES (:task_id, :assignee_user_id)");
            $stmt->bindParam(':task_id', $task_id);
            $stmt->bindParam(':assignee_user_id', $assignee_user_id);
            $stmt->execute();

            // GET ASSIGNEE USER
            $stmt = $conn->prepare("SELECT simplepush_key FROM users WHERE id = {$assignee_user_id} LIMIT 1");
            $stmt->execute();
            $user = $stmt->fetch();

            // GET TASK TITLE
            $query = "SELECT title FROM tasks WHERE task_id = {$task_id} LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $task = $stmt->fetch();

            if ( !empty($user['simplepush_key']) ){
                try{
                    $data = json_encode([
                        'event' => 'event',
                        'key' => $_SESSION['simplepush_key'],
                        'title' => "Σας	ανατέθηκε " . $task['title'],
                        'msg' => '',
                    ]);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://simplepu.sh');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $result = curl_exec($ch);
                    curl_close($ch);
                } catch(\Exception $e){
                    echo $e->getMessage();
                }
            }
        }

        header("Location: ../view_tasks.php?list_id=" . $list_id);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
