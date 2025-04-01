document.addEventListener('DOMContentLoaded', function() {
    // Кнопка добавления шага
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

    // Удаление шага
    document.querySelectorAll('.remove-step').forEach(button => {
        button.addEventListener('click', removeStep);
    });

    function removeStep(event) {
        const steps = document.getElementById('steps');
        if (steps.children.length > 1) { // Не удаляем последний шаг
            event.target.parentElement.remove();
        }
    }
});