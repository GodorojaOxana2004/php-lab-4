# Лабораторная работа №4. Обработка и валидация форм

## О проекте

Проект "Система управления задачами" разработан в рамках лабораторной работы №4 "Обработка и валидация форм" для освоения работы с HTML-формами в PHP. Основная цель — изучить отправку данных на сервер, их фильтрацию, валидацию и сохранение, а также реализовать базовое веб-приложение, которое станет основой для дальнейшего развития в курсе веб-разработки.

Проект позволяет добавлять задачи с различными параметрами (название, категория, описание, тэги, шаги выполнения), отображать две последние задачи на главной странице и просматривать полный список задач с пагинацией. Дополнительно реализована динамическая работа с шагами выполнения через JavaScript, что улучшает удобство использования.

---

## Инструкции по запуску проекта

1. **Установите PHP**  
   Убедитесь, что на вашем компьютере установлен PHP (рекомендуется версия 7.4 или выше). Скачать можно с [официального сайта](https://www.php.net/downloads).

2. **Скачайте проект**  
   Загрузите файлы проекта из репозитория или распакуйте архив в любую удобную директорию.

3. **Откройте проект в IDE**  
   Используйте любую среду разработки, например, Visual Studio Code, PhpStorm или другую.

4. **Перейдите в директорию `public`**  
   Откройте терминал и выполните команду:  
   ```
   cd public
   ```

5. **Запустите локальный сервер**  
   Выполните следующую команду для запуска встроенного сервера PHP:  
   ```
   php -S localhost:8080
   ```

6. **Откройте приложение в браузере**  
   Перейдите по адресу:  
   ```
   http://localhost:8080
   ```

---

## Структура проекта

```
task-manager/
├── public/                        
│   ├── index.php                  # Главная страница (2 последние задачи)
│   ├── js/                        
│   │   └── script.js              # JavaScript для динамических шагов
│   └── task/                    
│       ├── create.php             # Форма добавления задачи
│       └── index.php              # Список всех задач с пагинацией
├── src/                            
│   ├── handlers/                   
│   │   └── handle_task.php        # Обработчик формы
│   └── helpers.php                # Вспомогательные функции
├── storage/                        
│   └── tasks.txt                  # Файл для хранения задач (JSON)
└── README.md                      # Документация проекта
```

### Описание файлов
- **`public/index.php`**  
  Главная страница, отображающая две последние задачи из файла `storage/tasks.txt`. Использует PHP для чтения данных и HTML для вывода.

- **`public/task/create.php`**  
  Форма добавления задачи с полями для ввода данных (название, категория, описание, тэги, шаги). Поддерживает динамическое добавление шагов через JavaScript и отображает ошибки валидации.

- **`public/task/index.php`**  
  Страница со списком всех задач. Реализована пагинация (5 задач на страницу) с навигацией по страницам.

- **`src/handlers/handle_task.php`**  
  Обработчик формы. Выполняет фильтрацию данных, их валидацию и сохранение в файл `storage/tasks.txt`.

- **`src/helpers.php`**  
  Содержит вспомогательные функции для работы с данными формы, такие как `getFormValue()` и `isSelected()`.

- **`storage/tasks.txt`**  
  Текстовый файл для хранения задач. Каждая строка — это JSON-объект, представляющий задачу.

- **`public/js/script.js`**  
  JavaScript-код для управления шагами выполнения: добавление новых шагов и удаление существующих.

---

## Функциональность и фрагменты кода

### Добавление задачи (`public/task/create.php`)
Форма позволяет пользователю ввести данные задачи и динамически добавлять шаги выполнения.

#### Основные элементы формы:
- **Поля ввода**:  
  - Название задачи (`<input type="text">`).  
  - Категория (`<select>` с вариантами: "работа", "личное", "срочное").  
  - Описание (`<textarea>`).  
  - Тэги (`<select multiple>` с вариантами: "важно", "быстро", "сложно").  
  - Шаги выполнения — динамически добавляемые `<textarea>` через JavaScript.  
- **Ошибки валидации**: Отображаются под соответствующими полями в случае некорректного ввода.  
- **Навигация**: Ссылки на главную страницу, список задач и саму форму.

#### Пример кода формы:
```php
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
```

#### JavaScript для динамических шагов (`public/js/script.js`):
```javascript
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('add-step').addEventListener('click', function() {
        const steps = document.getElementById('steps');
        const newStep = document.createElement('div');
        newStep.className = 'step';
        newStep.innerHTML = `
            <textarea name="steps[]" rows="2"></textarea>
            <span class="remove-step">Удалить</span>
        `;
        steps.appendChild(newStep);
        newStep.querySelector('.remove-step').addEventListener('click', removeStep);
    });

    document.querySelectorAll('.remove-step').forEach(button => {
        button.addEventListener('click', removeStep);
    });

    function removeStep(event) {
        const steps = document.getElementById('steps');
        if (steps.children.length > 1) {
            event.target.parentElement.remove();
        }
    }
});
```

---

### Обработка данных формы (`src/handlers/handle_task.php`)
Обработчик принимает данные из формы, фильтрует их, проверяет на корректность и сохраняет в файл.

#### Основные этапы:
- **Фильтрация**: Используется `filter_input()` для защиты от XSS-атак.  
- **Валидация**: Проверка обязательных полей (название, категория, описание).  
- **Сохранение**: Данные записываются в `storage/tasks.txt` в формате JSON.

#### Пример кода:
```php
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) ?? [];
$steps = filter_input(INPUT_POST, 'steps', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) ?? [];

if (!$title) {
    $errors['title'] = 'Введите название задачи';
}
if (!$category) {
    $errors['category'] = 'Выберите категорию';
}
if (!$description) {
    $errors['description'] = 'Добавьте описание';
}

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
    file_put_contents($tasksFile, json_encode($task) . PHP_EOL, FILE_APPEND);
    header('Location: ../index.php');
    exit;
}
```

---

### Главная страница (`public/index.php`)
Отображает две последние задачи из файла `tasks.txt`.

#### Основные элементы:
- Чтение данных с помощью `file()`.  
- Декодирование JSON в массив через `array_map()`.  
- Выборка двух последних задач через `array_slice()`.

#### Пример кода:
```php
$tasksFile = __DIR__ . '/../storage/tasks.txt';
$tasks = [];
if (file_exists($tasksFile) && filesize($tasksFile) > 0) {
    $tasks = file($tasksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $tasks = array_map('json_decode', $tasks);
    $tasks = array_filter($tasks);
}
$latestTasks = array_slice($tasks, -2);

foreach ($latestTasks as $task) {
    echo "<h2>" . htmlspecialchars($task->title) . "</h2>";
    echo "<p>Категория: " . htmlspecialchars($task->category) . "</p>";
    echo "<p>" . htmlspecialchars($task->description) . "</p>";
    echo "<p>Тэги: " . implode(', ', array_map('htmlspecialchars', $task->tags)) . "</p>";
    // Вывод шагов
}
```

---

### Список задач с пагинацией (`public/task/index.php`)
Отображает все задачи с возможностью постраничного просмотра.

#### Основные элементы:
- **Пагинация**: 5 задач на страницу, навигация через ссылки "Назад", "Вперёд" и номера страниц.  
- **Вывод**: Данные читаются из файла и отображаются для текущей страницы.

#### Пример кода:
```php
$tasksFile = __DIR__ . '/../../storage/tasks.txt';
$tasks = [];
if (file_exists($tasksFile) && filesize($tasksFile) > 0) {
    $tasks = file($tasksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $tasks = array_map('json_decode', $tasks);
    $tasks = array_filter($tasks);
}

$perPage = 5;
$totalTasks = count($tasks);
$totalPages = ceil($totalTasks / $perPage);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $totalPages));
$start = ($page - 1) * $perPage;
$currentTasks = array_slice($tasks, $start, $perPage);

foreach ($currentTasks as $task) {
    echo "<h2>" . htmlspecialchars($task->title) . "</h2>";
    // Вывод других данных
}

if ($page > 1) {
    echo "<a href='index.php?page=" . ($page - 1) . "'>Назад</a> ";
}
for ($i = 1; $i <= $totalPages; $i++) {
    echo $i == $page ? "<strong>$i</strong> " : "<a href='index.php?page=$i'>$i</a> ";
}
if ($page < $totalPages) {
    echo "<a href='index.php?page=" . ($page + 1) . "'>Вперёд</a>";
}
```

---

### Вспомогательные функции (`src/helpers.php`)
Содержит функции для упрощения работы с формами.

#### Пример кода:
```php
/**
 * Возвращает значение поля формы или пустую строку
 */
function getFormValue($field) {
    return htmlspecialchars($_POST[$field] ?? '');
}

/**
 * Проверяет, выбрано ли значение в выпадающем списке
 */
function isSelected($field, $value, $isArray = false) {
    if ($isArray) {
        return in_array($value, $_POST[$field] ?? []) ? 'selected' : '';
    }
    return ($_POST[$field] ?? '') === $value ? 'selected' : '';
}
```

---

## Ответы на контрольные вопросы

1. **Какие методы HTTP применяются для отправки данных формы?**  
   - `GET`: Данные передаются через URL (например, для поиска).  
   - `POST`: Данные отправляются в теле запроса (используется в проекте для форм).  

2. **Что такое валидация данных, и чем она отличается от фильтрации?**  
   - **Валидация**: Проверка корректности данных (например, поле не пустое).  
   - **Фильтрация**: Очистка данных от опасного содержимого (например, защита от XSS).  
   В проекте: валидация — проверка на пустые поля, фильтрация — через `filter_input()`.

3. **Какие функции PHP используются для фильтрации данных?**  
   - `filter_input()`: Фильтрация входных данных (применяется в обработчике).  
   - `filter_var()`: Фильтрация переменных.  
   - `htmlspecialchars()`: Экранирование для безопасного вывода (используется при отображении).

---

## Использованные источники

1. Официальная документация PHP: [https://www.php.net/manual/ru/](https://www.php.net/manual/ru/)  
2. Условие лабораторной работы на GitHub.  
3. Лекционные материалы курса.  
4. Документация MDN по JavaScript: [https://developer.mozilla.org/ru/docs/Web/JavaScript](https://developer.mozilla.org/ru/docs/Web/JavaScript)  

---

## Дополнительные аспекты

- **Динамические шаги**: Реализована возможность добавлять и удалять шаги выполнения через JavaScript, что делает интерфейс интерактивным.  
- **Пагинация**: Удобный постраничный вывод задач улучшает навигацию при большом количестве записей.  
- **Документация**: Код снабжён PHPDoc-комментариями для упрощения поддержки и понимания другими разработчиками.  
- **Безопасность**: Применены фильтрация и экранирование данных для защиты от XSS-атак.
