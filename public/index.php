<?php
$tasksFile = __DIR__ . '/../storage/tasks.txt';
$tasks = [];

if (file_exists($tasksFile) && filesize($tasksFile) > 0) {
    $tasks = file($tasksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    // Корректно декодируем JSON в массив
    $tasks = array_map(function($line) { return json_decode($line, true); }, $tasks);
    // Удаляем null значения, которые могли появиться из-за ошибок декодирования
    $tasks = array_filter($tasks, function($task) { return $task !== null; });
}
// Берем последние 2 задачи
$latestTasks = array_slice($tasks, -2);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Система управления задачами</title>
</head>
<body>
    <nav>
        <a href="index.php">Главная</a> |
        <a href="task/index.php">Все задачи</a> |
        <a href="task/create.php">Добавить задачу</a>
    </nav>
    <h1>Последние задачи</h1>
    <?php if (empty($latestTasks)): ?>
        <p>Пока нет задач.</p>
    <?php else: ?>
        <?php foreach ($latestTasks as $task): ?>
            <h2><?= htmlspecialchars($task['title'] ?? 'Без названия') ?></h2>
            <p>Категория: <?= htmlspecialchars($task['category'] ?? 'Не указана') ?></p>
            <p><?= htmlspecialchars($task['description'] ?? 'Нет описания') ?></p>
            <p>Тэги: <?= implode(', ', array_map('htmlspecialchars', $task['tags'] ?? [])) ?></p>
            <p>Шаги: <?= nl2br(htmlspecialchars($task['steps'] ?? 'Нет шагов')) ?></p>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>