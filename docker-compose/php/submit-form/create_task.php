<?php
session_start();
require '../conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $list_id = $_POST['list_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    try {
        $stmt = $conn->prepare("INSERT INTO tasks (title, description, status, list_id) VALUES (:title, :description, :status, :list_id)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':list_id', $list_id);
        $stmt->execute();
        //$id = $conn->lastInsertId();

        if ( !empty($_SESSION['simplepush_key']) ){
            try{
                $data = json_encode([
                    'event' => 'event',
                    'key' => $_SESSION['simplepush_key'],
                    'title' => $title,
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

        header("Location: ../view_tasks.php?list_id=" . $list_id);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
