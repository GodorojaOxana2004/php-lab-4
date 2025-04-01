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
    <style>
        .step { margin: 10px 0; display: flex; }
        .step textarea { width: 80%; margin-right: 10px; }
        .remove-step { color: red; cursor: pointer; }
        .error { color: red; }
    </style>
</head>
<body>
    <nav>
        <a href="../index.php">Главная</a> |
        <a href="index.php">Все задачи</a> |
        <a href="create.php">Добавить задачу</a>
    </nav>
    <h1>Добавить задачу</h1>

    <form method="POST">
        <div>
            <label>Название:</label><br>
            <input type="text" name="title" value="<?= getFormValue('title') ?>">
            <?php if (isset($errors['title'])): ?>
                <p class="error"><?= $errors['title'] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label>Категория:</label><br>
            <select name="category">
                <option value="работа" <?= isSelected('category', 'работа') ?>>Работа</option>
                <option value="личное" <?= isSelected('category', 'личное') ?>>Личное</option>
                <option value="срочное" <?= isSelected('category', 'срочное') ?>>Срочное</option>
            </select>
            <?php if (isset($errors['category'])): ?>
                <p class="error"><?= $errors['category'] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label>Описание:</label><br>
            <textarea name="description"><?= getFormValue('description') ?></textarea>
            <?php if (isset($errors['description'])): ?>
                <p class="error"><?= $errors['description'] ?></p>
            <?php endif; ?>
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
            <label>Шаги:</label><br>
            <div id="steps">
                <div class="step">
                    <textarea name="steps[]" rows="2"></textarea>
                    <span class="remove-step">Удалить</span>
                </div>
            </div>
            <button type="button" id="add-step">Добавить шаг</button>
        </div>

        <button type="submit">Сохранить</button>
    </form>

    <script src="../js/script.js"></script>
</body>
</html>