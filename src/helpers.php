<?php
/**
 * Вспомогательные функции для обработки данных формы
 */

/**
 * Получает значение поля формы из POST или возвращает пустую строку
 * @param string $field Название поля
 * @return string
 */
function getFormValue($field) {
    return htmlspecialchars($_POST[$field] ?? '');
}

/**
 * Проверяет, выбрано ли значение в select
 * @param string $field Название поля
 * @param string $value Значение для проверки
 * @param bool $isArray Флаг множественного выбора
 * @return string
 */
function isSelected($field, $value, $isArray = false) {
    if ($isArray) {
        return in_array($value, $_POST[$field] ?? []) ? 'selected' : '';
    }
    return ($_POST[$field] ?? '') === $value ? 'selected' : '';
}