<?php
require_once __DIR__ . '/../helpers.php';

$errors = [];

// Фильтруем данные из формы
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) ?? [];
$steps = filter_input(INPUT_POST, 'steps', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) ?? [];

// Проверяем поля
if (!$title) {
    $errors['title'] = 'Введите название задачи';
}
if (!$category) {
    $errors['category'] = 'Выберите категорию';
}
if (!$description) {
    $errors['description'] = 'Добавьте описание';
}

// Если ошибок нет, сохраняем задачу
if (empty($errors)) {
    $task = [
        'title' => $title,
        'category' => $category,
        'description' => $description,
        'tags' => $tags,
        'steps' => array_filter($steps), // Убираем пустые шаги
        'created_at' => date('Y-m-d H:i:s')
    ];

    $tasksFile = __DIR__ . '/../../storage/tasks.txt';
    if (!file_exists(dirname($tasksFile))) {
        mkdir(dirname($tasksFile), 0777, true);
    }
    file_put_contents($tasksFile, json_encode($task) . PHP_EOL, FILE_APPEND);
    header('Location: ../index.php');
    exit;
}