<?php
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