<?php
$tasksFile = __DIR__ . '/../../storage/tasks.txt';
$tasks = [];

if (file_exists($tasksFile) && filesize($tasksFile) > 0) {
    $tasks = file($tasksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $tasks = array_map(function($line) { return json_decode($line, true); }, $tasks);
    $tasks = array_filter($tasks, function($task) { return $task !== null; });
}

// Пагинация
$perPage = 5; // Количество задач на страницу
$totalTasks = count($tasks); // Общее количество задач
$totalPages = ceil($totalTasks / $perPage); // Общее количество страниц

// Получаем текущую страницу из GET-параметра, по умолчанию 1
$page = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $totalPages)) : 1;

// Вычисляем начальный индекс и получаем задачи для текущей страницы
$start = ($page - 1) * $perPage;
$currentTasks = array_slice($tasks, $start, $perPage);
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
        <?php foreach ($currentTasks as $task): ?>
            <h2><?= htmlspecialchars($task['title'] ?? 'Без названия') ?></h2>
            <p>Категория: <?= htmlspecialchars($task['category'] ?? 'Не указана') ?></p>
            <p><?= htmlspecialchars($task['description'] ?? 'Нет описания') ?></p>
            <p>Тэги: <?= implode(', ', array_map('htmlspecialchars', $task['tags'] ?? [])) ?></p>
            <p>Шаги:</p>
            <?php if (!empty($task['steps']) && is_array($task['steps'])): ?>
                <ol>
                    <?php foreach ($task['steps'] as $step): ?>
                        <li><?= htmlspecialchars($step) ?></li>
                    <?php endforeach; ?>
                </ol>
            <?php else: ?>
                <p>Нет шагов</p>
            <?php endif; ?>
            <hr>
        <?php endforeach; ?>
        
        <!-- Навигация по страницам -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="index.php?page=<?= $page - 1 ?>">Предыдущая</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i === $page): ?>
                    <strong><?= $i ?></strong>
                <?php else: ?>
                    <a href="index.php?page=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="index.php?page=<?= $page + 1 ?>">Следующая</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>