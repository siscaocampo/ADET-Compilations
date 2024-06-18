<?php
session_start();

if (!isset($_SESSION['todos'])) {
    $_SESSION['todos'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $id = $_POST['id'] ?? null;
        $task = $_POST['task'] ?? null;

        switch ($action) {
            case 'create':
                $_SESSION['todos'][] = ['task' => $task, 'completed' => false];
                break;
            case 'update':
                if ($id !== null && isset($_SESSION['todos'][$id])) {
                    $_SESSION['todos'][$id]['task'] = $task;
                }
                break;
            case 'delete':
                if ($id !== null) {
                    array_splice($_SESSION['todos'], $id, 1);
                }
                break;
            case 'toggle':
                if ($id !== null && isset($_SESSION['todos'][$id])) {
                    $_SESSION['todos'][$id]['completed'] = !$_SESSION['todos'][$id]['completed'];
                }
                break;
        }
    }
    header('Location: index.php');
    exit;
}

$todos = $_SESSION['todos'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime To-Do App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Anime To-Do App</h1>
    </header>
    <main>
        <section id="todo-form">
            <h2>Add New Task</h2>
            <form action="index.php" method="POST">
                <input type="hidden" name="action" value="create">
                <input type="text" name="task" required>
                <button type="submit">Add Task</button>
            </form>
        </section>
        <section id="todo-list">
            <h2>Your Tasks</h2>
            <ul>
                <?php foreach ($todos as $index => $todo): ?>
                    <li>
                        <form action="index.php" method="POST">
                            <input type="hidden" name="id" value="<?= $index ?>">
                            <input type="hidden" name="action" value="toggle">
                            <button type="submit"><?= $todo['completed'] ? 'Undo' : 'Complete' ?></button>
                        </form>
                        <span class="<?= $todo['completed'] ? 'completed' : '' ?>"><?= htmlspecialchars($todo['task']) ?></span>
                        <form action="index.php" method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $index ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit">Delete</button>
                        </form>
                        <form action="index.php" method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= $index ?>">
                            <input type="hidden" name="action" value="update">
                            <input type="text" name="task" value="<?= htmlspecialchars($todo['task']) ?>" required>
                            <button type="submit">Update</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>
</html>
