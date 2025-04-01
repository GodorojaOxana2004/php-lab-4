### Отчет по лабораторной работе №4: Обработка и валидация форм

#### Инструкции по запуску проекта
1. Установите PHP (если не установлен).
2. Скачайте файлы проекта.
3. Откройте проект в Visual Studio Code или другой IDE.
4. Перейдите в директорию `public` через терминал с помощью команды:  
   ```
   cd public
   ```
5. Запустите локальный сервер командой:  
   ```
   php -S localhost:8080
   ```
6. Откройте браузер и перейдите по адресу:  
   ```
   http://localhost:8080
   ```

#### Описание лабораторной работы
**Цель работы** – освоить основные принципы работы с HTML-формами в PHP, включая отправку данных на сервер, их фильтрацию, валидацию и сохранение. В рамках работы реализован проект "Система управления задачами", который позволяет добавлять задачи с различными параметрами, отображать последние задачи на главной странице и просматривать полный список задач с пагинацией. Также реализована динамическая работа с шагами выполнения задач с использованием JavaScript.

Проект стал основой для дальнейшего изучения разработки веб-приложений и был расширен дополнительными функциями для повышения удобства использования.

#### Краткая документация к проекту
- **Файл `public/index.php`** – главная страница, отображающая две последние задачи из файла `storage/tasks.txt`.
- **Файл `public/task/create.php`** – форма добавления задачи с полями для ввода данных, динамическим добавлением шагов выполнения через JavaScript и отображением ошибок валидации.
- **Файл `public/task/index.php`** – страница со списком всех задач с реализованной пагинацией (по 5 задач на страницу).
- **Файл `src/handlers/handle_task.php`** – обработчик формы, выполняющий фильтрацию, валидацию и сохранение данных.
- **Файл `src/helpers.php`** – вспомогательные функции для работы с данными формы.
- **Файл `storage/tasks.txt`** – текстовый файл для хранения задач в формате JSON.
- **Файл `public/js/script.js`** – JavaScript-код для динамического добавления и удаления шагов в форме.

#### Фрагменты кода, описание выполнения заданий

##### `public/task/create.php`
Файл содержит HTML-форму для добавления задачи с динамическим управлением шагами выполнения. Основные элементы:
- Поля формы:
  - Название задачи (`<input type="text">`);
  - Категория (`<select>` с вариантами: "работа", "личное", "срочное");
  - Описание (`<textarea>`);
  - Тэги (`<select multiple>` с вариантами: "важно", "быстро", "сложно");
  - Шаги выполнения – динамически добавляемые поля (`<textarea>`) с помощью JavaScript. Пользователь может добавлять новые шаги кнопкой "Добавить шаг" и удалять их кнопкой "Убрать".
- Обработка ошибок валидации: ошибки отображаются под соответствующими полями.
- Навигация: ссылки на главную страницу, список задач и форму добавления.

Пример кода формы с динамическими шагами:
```php
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
```

JavaScript-код для управления шагами (в файле `public/js/script.js`):
```javascript
document.getElementById('add-step-btn').addEventListener('click', function() {
    const container = document.getElementById('steps-container');
    const stepDiv = document.createElement('div');
    stepDiv.className = 'step-container';
    stepDiv.innerHTML = `
        <textarea name="steps[]" rows="2"></textarea>
        <span class="remove-step">Убрать</span>
    `;
    container.appendChild(stepDiv);
});

document.addEventListener('click', function(e) {
    if (e.target.className === 'remove-step') {
        e.target.parentElement.remove();
    }
});
```

##### `src/handlers/handle_task.php`
Обработчик формы выполняет фильтрацию, валидацию и сохранение данных. Основные элементы:
- Фильтрация данных с помощью `filter_input()` для защиты от XSS.
- Валидация: проверка на пустые значения для полей "Название", "Категория" и "Описание".
- Обработка массива шагов: шаги принимаются как массив (`steps[]`) и сохраняются в JSON.
- Сохранение: данные записываются в файл `storage/tasks.txt` в формате JSON с добавлением метки времени.

Пример кода обработки:
```php
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tags = filter_input(INPUT_POST, 'tags', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
$steps = filter_input(INPUT_POST, 'steps', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];

if (empty($title)) {
    $errors['title'] = 'Название задачи обязательно';
}
if (empty($category)) {
    $errors['category'] = 'Категория обязательна';
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
        'steps' => array_filter($steps, 'trim'), // Удаляем пустые шаги
        'created_at' => date('Y-m-d H:i:s')
    ];
    file_put_contents($tasksFile, json_encode($task) . PHP_EOL, FILE_APPEND | LOCK_EX);
    header('Location: ../../index.php');
    exit;
}
```

##### `public/index.php`
Отображает две последние задачи. Основные элементы:
- Чтение данных из файла с помощью `file()`.
- Декодирование JSON-строк в массив с помощью `array_map()`.
- Вывод последних двух задач с использованием `array_slice()`.

Пример кода:
```php
$tasks = file($tasksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$tasks = array_map(function($line) { return json_decode($line, true); }, $tasks);
$latestTasks = array_slice($tasks, -2);
foreach ($latestTasks as $task) {
    echo "<h2>" . htmlspecialchars($task['title']) . "</h2>";
    // Вывод других данных
}
```

##### `public/task/index.php`
Отображает все задачи с пагинацией. Основные элементы:
- Чтение и декодирование данных аналогично `index.php`.
- Реализация пагинации: 5 задач на страницу, с навигацией "Предыдущая", "Следующая" и номерами страниц.
- Вывод задач в цикле `foreach` для текущей страницы.

Пример кода пагинации:
```php
$perPage = 5;
$totalTasks = count($tasks);
$totalPages = ceil($totalTasks / $perPage);
$page = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $totalPages)) : 1;
$start = ($page - 1) * $perPage;
$currentTasks = array_slice($tasks, $start, $perPage);

foreach ($currentTasks as $task) {
    echo "<h2>" . htmlspecialchars($task['title'] ?? 'Без названия') . "</h2>";
    // Вывод других данных
}

if ($page > 1) {
    echo "<a href='index.php?page=" . ($page - 1) . "'>Предыдущая</a> ";
}
for ($i = 1; $i <= $totalPages; $i++) {
    if ($i === $page) {
        echo "<strong>$i</strong> ";
    } else {
        echo "<a href='index.php?page=$i'>$i</a> ";
    }
}
if ($page < $totalPages) {
    echo "<a href='index.php?page=" . ($page + 1) . "'>Следующая</a>";
}
```

##### `src/helpers.php`
Содержит вспомогательные функции:
- `getFormValue()` – возвращает значение поля формы или пустую строку, поддерживает массивы.
- `isSelected()` – проверяет, выбрано ли значение в `<select>`.

Пример кода:
```php
function getFormValue($field) {
    if (isset($_POST[$field])) {
        return is_array($_POST[$field]) ? $_POST[$field] : htmlspecialchars($_POST[$field]);
    }
    return '';
}
```

#### Ответы на контрольные вопросы
1. **Какие методы HTTP применяются для отправки данных формы?**  
   - `GET` – данные передаются в URL (подходит для простых запросов, например, поиска).  
   - `POST` – данные отправляются в теле запроса (используется для форм с конфиденциальными данными или большими объемами информации). В проекте используется `POST`.

2. **Что такое валидация данных, и чем она отличается от фильтрации?**  
   - **Валидация** – проверка данных на соответствие требованиям (например, поле не пустое).  
   - **Фильтрация** – очистка данных от нежелательного содержимого (например, удаление тегов для защиты от XSS).  
   В проекте фильтрация выполняется через `filter_input()`, а валидация – через проверки на пустые значения.

3. **Какие функции PHP используются для фильтрации данных?**  
   - `filter_input()` – фильтрует входные данные (используется в проекте).  
   - `filter_var()` – фильтрует переменные.  
   - `htmlspecialchars()` – преобразует специальные символы в HTML-сущности (используется для вывода).

#### Список использованных источников
1. Официальная документация PHP: [https://www.php.net/manual/ru/](https://www.php.net/manual/ru/)
2. Условие лабораторной работы на GitHub.
3. Примеры кода из лекций.
4. Документация MDN по JavaScript: [https://developer.mozilla.org/ru/docs/Web/JavaScript](https://developer.mozilla.org/ru/docs/Web/JavaScript)

#### Дополнительные важные аспекты
- Реализована динамическая работа с шагами выполнения задач через JavaScript, что улучшает пользовательский опыт.
- Добавлена пагинация на странице списка задач, что делает проект более удобным при большом количестве данных.
- Код задокументирован с использованием PHPDoc для улучшения читаемости и поддержки другими разработчиками.
