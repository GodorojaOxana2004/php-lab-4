document.addEventListener('DOMContentLoaded', function() {
    // Добавление нового шага
    document.getElementById('add-step-btn').addEventListener('click', function() {
        const container = document.getElementById('steps-container');
        const stepDiv = document.createElement('div');
        stepDiv.className = 'step-container';
        stepDiv.innerHTML = `
            <textarea name="steps[]" rows="2"></textarea>
            <span class="remove-step">Убрать</span>
        `;
        container.appendChild(stepDiv);
        // Добавляем обработчик удаления для нового шага
        stepDiv.querySelector('.remove-step').addEventListener('click', removeStep);
    });

    // Добавление обработчиков удаления для существующих шагов
    document.querySelectorAll('.remove-step').forEach(button => {
        button.addEventListener('click', removeStep);
    });

    // Функция удаления шага
    function removeStep(event) {
        const container = document.getElementById('steps-container');
        if (container.children.length > 1) {
            event.target.parentElement.remove();
        }
    }
});