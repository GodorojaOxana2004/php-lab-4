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
        .step-container { 
            margin: 10px 0; 
            display: flex; 
            align-items: center; 
        }
        .step-container textarea { 
            width: 100%; 
            margin-right: 10px; 
        }
        .remove-step { 
            color: red; 
            cursor: pointer; 
            font-size: 18px; 
        }
    </style>
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
            <div id="steps-container">
                <?php
                $steps = getFormValue('steps');
                if ($steps) {
                    $steps_array = is_array($steps) ? $steps : explode("\n", $steps);
                    foreach ($steps_array as $index => $step) {
                        if (trim($step) !== '') {
                            echo '<div class="step-container">';
                            echo '<textarea name="steps[]" rows="2">' . htmlspecialchars(trim($step)) . '</textarea>';
                            echo '<span class="remove-step">Убрать</span>';
                            echo '</div>';
                        }
                    }
                } else {
                    echo '<div class="step-container">';
                    echo '<textarea name="steps[]" rows="2"></textarea>';
                    echo '<span class="remove-step">Убрать</span>';
                    echo '</div>';
                }
                ?>
            </div>
            <button type="button" id="add-step-btn">Добавить шаг</button>
        </div>

        <button type="submit">Отправить</button>
    </form>

    <script src="../js/script.js"></script>
</body>
</html>