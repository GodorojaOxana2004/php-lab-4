<?php
$tasksFile = __DIR__ . '/../../storage/tasks.txt';
$tasks = [];

if (file_exists($tasksFile) && filesize($tasksFile) > 0) {
    $tasks = file($tasksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    // Исправляем array_map для корректной передачи аргумента assoc
    $tasks = array_map(function($line) { return json_decode($line, true); }, $tasks);
    // Фильтруем null значения, чтобы избежать ошибок
    $tasks = array_filter($tasks, function($task) { return $task !== null; });
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Все задачи</title>
</head>
<body>
    <nav>
        <a href="../index.php">Главная</a> |
        <a href="index.php">Все задачи</a> |
        <a href="create.php">Добавить задачу</a>
    </nav>
    <h1>Все задачи</h1>
    <?php if (empty($tasks)): ?>
        <p>Пока нет задач.</p>
    <?php else: ?>
        <?php foreach ($tasks as $task): ?>
            <h2><?= htmlspecialchars($task['title']) ?></h2>
            <p>Категория: <?= htmlspecialchars($task['category']) ?></p>
            <p><?= htmlspecialchars($task['description']) ?></p>
            <p>Тэги: <?= implode(', ', array_map('htmlspecialchars', $task['tags'] ?? [])) ?></p>
            <p>Шаги: <?= nl2br(htmlspecialchars($task['steps'] ?? '')) ?></p>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- TODO: Реализовать пагинацию (5 задач на страницу) -->
</body>
</html>