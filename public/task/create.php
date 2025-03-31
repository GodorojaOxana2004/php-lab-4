<?php
require_once __DIR__ . '/../../src/helpers.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../src/handlers/handle_task.php';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить задачу</title>
</head>
<body>
    <nav>
        <a href="../index.php">Главная</a> |
        <a href="index.php">Все задачи</a> |
        <a href="create.php">Добавить задачу</a>
    </nav>
    <h1>Добавить задачу</h1>
    <form method="POST" action="">
        <div>
            <label>Название задачи:</label><br>
            <input type="text" name="title" value="<?= getFormValue('title') ?>">
            <?php if (isset($errors['title'])): ?><p style="color:red"><?= $errors['title'] ?></p><?php endif; ?>
        </div>
        <div>
            <label>Категория:</label><br>
            <select name="category">
                <option value="работа" <?= isSelected('category', 'работа') ?>>Работа</option>
                <option value="личное" <?= isSelected('category', 'личное') ?>>Личное</option>
                <option value="срочное" <?= isSelected('category', 'срочное') ?>>Срочное</option>
            </select>
            <?php if (isset($errors['category'])): ?><p style="color:red"><?= $errors['category'] ?></p><?php endif; ?>
        </div>
        <div>
            <label>Описание:</label><br>
            <textarea name="description"><?= getFormValue('description') ?></textarea>
            <?php if (isset($errors['description'])): ?><p style="color:red"><?= $errors['description'] ?></p><?php endif; ?>
        </div>
        <div>
            <label>Тэги:</label><br>
            <select name="tags[]" multiple>
                <option value="важно" <?= isSelected('tags', 'важно', true) ?>>Важно</option>
                <option value="быстро" <?= isSelected('tags', 'быстро', true) ?>>Быстро</option>
                <option value="сложно" <?= isSelected('tags', 'сложно', true) ?>>Сложно</option>
            </select>
        </div>
        <div>
            <label>Шаги выполнения:</label><br>
            <textarea name="steps"><?= getFormValue('steps') ?></textarea>
        </div>
        <button type="submit">Отправить</button>
    </form>
</body>
</html>