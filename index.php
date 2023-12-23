<?php

$conn = new mysqli("Localhost", "root", "", "todo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add_task'])) {
    $task_name = $_POST['task_name'];
    $sql = "INSERT INTO tasks (task_name) VALUES ('$task_name')";
    $conn->query($sql);
}

if (isset($_GET['action'])) {
    $id = $_GET['id'];
    switch ($_GET['action']) {
        case 'delete':
            $conn->query("DELETE FROM tasks WHERE id=$id");
            break;
        case 'update':
            $status = $_GET['status'];
            $conn->query("UPDATE tasks SET status='$status' WHERE id=$id");
            break;
        case 'edit':
            $result = $conn->query("SELECT * FROM tasks WHERE id=$id");
            $task = $result->fetch_assoc();
            break;
        case 'canceled': 
            $conn->query("UPDATE tasks SET status='canceled' WHERE id=$id");
            break;

    }
}

$result = $conn->query("SELECT * FROM tasks");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>ToDo List</title>
</head>

<body>
    <div class="container mt-5 col-lg-8">
        <h2 style="text-align:center;">ToDo List</h2>
        <h3 class="mt-5">Tasks</h3>
        <form method="post" class="mb-3">
            <div class="input-group">
                <input type="text" name="task_name" class="form-control" placeholder="What Do You Need To Do?"  required>
                
            </div>
            <div class="mt-3" style="text-align: right;">
                <button type="submit" name="add_task" class="btn btn-primary">Save Item</button>
            </div>
        </form>

        <div>
            <h2>To Do:</h2>
        </div>

        <ul class="list-group">
            <?php while ($row = $result->fetch_assoc()) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $row['task_name']; ?>
                    <div class="btn-group">
                        <button type="button" class="btn mr-2 <?php echo ($row['status'] == 'completed') ? 'btn-success' : (($row['status'] == 'canceled') ? 'btn-primary' : 'btn btn-dark'); ?> dropdown-toggle" data-toggle="dropdown">
                            <?php echo ucfirst($row['status']); ?>
                        </button>
                        <div class="dropdown-menu " style="text-align: right;" >
                            <a class="dropdown-item" href="?action=update&id=<?php echo $row['id']; ?>&status=completed">Completed</a>
                            <a class="dropdown-item" href="?action=update&id=<?php echo $row['id']; ?>&status=pending">Pending</a>
                            <a class="dropdown-item" href="?action=canceled&id=<?php echo $row['id']; ?>&status=canceled">Canceled</a>
                        </div>
                        <div style="text-align: right;" class="btn mr-2">
                         <a href="?action=delete&id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-danger">Delete</button></a>
                        </div>
                        <div style="text-align: right;">
                        <a href="?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                        </div>
                    </div>
                    
                </li>
                
            <?php endwhile; ?>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>