<?php
/**
 * Обработчик формы добавления задачи
 */

require_once __DIR__ . '/../helpers.php';

$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) ?? [];
$steps = filter_input(INPUT_POST, 'steps', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Валидация
if (empty($title)) {
    $errors['title'] = 'Название задачи обязательно';
}
if (empty($category)) {
    $errors['category'] = 'Выберите категорию';
}
if (empty($description)) {
    $errors['description'] = 'Описание обязательно';
}

if (empty($errors)) {
    $task = [
        'title' => $title,
        'category' => $category,
        'description' => $description,
        'tags' => $tags,
        'steps' => $steps,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $tasksFile = __DIR__ . '/../../storage/tasks.txt';
    if (!file_exists(dirname($tasksFile))) {
        mkdir(dirname($tasksFile), 0777, true);
    }
    file_put_contents($tasksFile, json_encode($task) . PHP_EOL, FILE_APPEND | LOCK_EX); // Добавляем блокировку
    header('Location: ../../index.php'); // Исправлен путь перенаправления
    exit;
}