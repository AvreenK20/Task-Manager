<?php
session_start();
if (!isset($_SESSION["User"])) {
    header("Location: login.php");
}

require_once "database.php"; 

if(isset($_POST['edit_task'])) {
    $task_id = $_POST['task_id'];

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
    <title>Edit Task</title>
</head>

<body>
    <div class="top-nav">
        <div>
            <img src="./icon.png" alt="" class="logo">
        </div>
        <div>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
    </div>

    <div class="midnav">
        <h1>Edit Task</h1>
    </div>

    <div class="create-task">
        <h2>Update Task Fields</h2>
        <form action="update_task.php" method="post">
            <input type="hidden" name="task_id" value="<?php echo $task['TaskID']; ?>">
            <input type="text" name="task_title" placeHolder="Title" value="<?php echo $task['Title']; ?>"
                required>
            <textarea name="task_description" placeholder="Description"
                rows="4" required><?php echo $task['Description']; ?></textarea>
            <label for="date">Due Date:</label>
            <input type="date" name="due_date" value="<?php echo $task['DueDate']; ?>" required>

            <label for="priority">Priority:</label>
            <select id="priority" name="priority" required>
                <option value="Low" <?php if ($task['Priority'] == 'Low') echo 'selected'; ?>>Low</option>
                <option value="Medium" <?php if ($task['Priority'] == 'Medium') echo 'selected'; ?>>Medium</option>
                <option value="High" <?php if ($task['Priority'] == 'High') echo 'selected'; ?>>High</option>
            </select>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="Incomplete" <?php if ($task['Status'] == 'Incomplete') echo 'selected'; ?>>Incomplete
                </option>
                <option value="Inprogress" <?php if ($task['Status'] == 'Inprogress') echo 'selected'; ?>>Inprogress
                </option>
                <option value="Complete" <?php if ($task['Status'] == 'Complete') echo 'selected'; ?>>Complete</option>
            </select>
            <button type="submit" name="update_task">Update Task</button>
        </form>
    </div>
</body>

</html>
