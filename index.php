<?php
session_start();
if (!isset($_SESSION["User"])) {
    header("Location: login.php");
}

require_once "database.php"; 

// Fetch tasks for the logged-in user
$user_id = $_SESSION["UserID"]; 
$sql = "SELECT * FROM tasks WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$tasks = array();
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

if(isset($_POST['create_task'])) {

    $title = $_POST['task_title'];
    $description = $_POST['task_description'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $status = "incomplete";

    $stmt = $conn->prepare("INSERT INTO tasks (Title, Description, DueDate, Priority, Status, UserID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $description, $due_date, $priority, $status, $user_id);

    if ($stmt->execute() === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}

$task_id = $_POST['task_id'];

if(isset($_POST['delete_task'])) {

    $sql = "DELETE FROM tasks WHERE TaskID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $task_id);
    
    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error deleting task: ";
    }
}

if(isset($_POST['edit_task'])) {
    $sql = "SELECT * FROM tasks WHERE TaskID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css">
    <title>User Dashboard</title>
</head>

<body>
    <div class="top-nav">
        <div>
            <img src="./icon.png" alt="" class="logo">
        </div>
        <div>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li class="active"><a href="index.php">Dashboard</a></li>
                <?php
                    echo '<li><a href="profile.php">Profile</a></li>';
                    echo '<li><a href="logout.php">Log Out</a></li>'; 
                ?>
            </ul>
        </div>
    </div>

    <div class="midnav">
        <h1>Welcome, <?php echo $_SESSION["FullName"]; ?>!</h1>
    </div>

    <div>
        <div class="create-task">
            <h2>Create Task</h2>
            <form action="" method="post">
                <input type="text" name="task_title" placeholder="Title" required>
                <textarea name="task_description" placeholder="Description" rows="4" required></textarea>
                <label for="date">Due Date:</label>
                <input type="date" name="due_date" required>
                <label for="priority">Priority:</label>
                <select name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
                <button type="submit" name="create_task">Create Task</button>
                <input type='hidden' name='task_id' value='<?php echo $task["TaskID"]; ?>'>
            </form>
        </div>
    </div>

    <div class="task-list">
        <h2>Your Tasks:</h2>
        <ul>
            <?php
            foreach($tasks as $task) {
                echo "<div class='sticky-note'> 
                        <li>
                        <span class='task-title'>$task[Title]</span>
                        <span>($task[Status])<span>
                        <br>
                        <br>
                        <span>$task[Description]</span>
                        <span class='task-buttons'>
                            <form action='edit_task.php' method='post'>
                                <input type='hidden' name='task_id' value='$task[TaskID]'>
                                <button type='submit' name='edit_task'>Edit</button>
                            </form>
                            <form action='' method='post' class='delete-form'>
                                <input type='hidden' name='task_id' value='$task[TaskID]'>
                                <button type='submit' name='delete_task'>Delete</button>
                            </form>
                        </span>
                      </li>
                      </div>";
                }
            ?>
        </ul>
        </div>
    </div> 
</body>

</html>
