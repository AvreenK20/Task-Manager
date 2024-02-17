<?php
session_start();
require_once "database.php"; 

if (!isset($_SESSION["User"])) {
    header("Location: login.php");
    exit; 
}

if(isset($_POST['update_task'])) {
    $task_id = $_POST['task_id'];
    $title = $_POST['task_title'];
    $description = $_POST['task_description'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tasks SET Title = ?, Description = ?, DueDate = ?, Priority = ?, Status = ? WHERE TaskID = ?");
    $stmt->bind_param("sssssi", $title, $description, $due_date, $priority, $status, $task_id);

    if ($stmt->execute() === TRUE) {
        header("Location: index.php");
        exit; 
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
