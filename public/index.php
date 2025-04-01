<?php
// Путь к файлу с задачами
$tasksFile = __DIR__ . '/../storage/tasks.txt';
$tasks = [];

// Читаем задачи из файла, если он существует
if (file_exists($tasksFile) && filesize($tasksFile) > 0) {
    $tasks = file($tasksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $tasks = array_map('json_decode', $tasks); // Преобразуем строки JSON в массив
    $tasks = array_filter($tasks); // Убираем пустые элементы
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
            <h2><?= htmlspecialchars($task->title) ?></h2>
            <p>Категория: <?= htmlspecialchars($task->category) ?></p>
            <p><?= htmlspecialchars($task->description) ?></p>
            <p>Тэги: <?= implode(', ', array_map('htmlspecialchars', $task->tags)) ?></p>
            <p>Шаги:</p>
            <?php if (!empty($task->steps)): ?>
                <ol>
                    <?php foreach ($task->steps as $step): ?>
                        <li><?= htmlspecialchars($step) ?></li>
                    <?php endforeach; ?>
                </ol>
            <?php else: ?>
                <p>Нет шагов.</p>
            <?php endif; ?>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>