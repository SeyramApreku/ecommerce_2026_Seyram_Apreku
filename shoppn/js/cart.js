document.addEventListener('DOMContentLoaded', function () {
    const error = document.getElementById('cart-validation-error');
    const updateForm = document.getElementById('cart-update-form');
    const removeForm = document.getElementById('cart-remove-form');

    function showError(message) {
        error.textContent = message;
        error.hidden = false;
    }

    if (updateForm) {
        updateForm.addEventListener('submit', function (event) {
            const inputs = document.querySelectorAll('input[form="cart-update-form"][name="qty[]"]');
            for (const input of inputs) {
                if (!/^[1-9]\d{0,2}$/.test(input.value)) {
                    event.preventDefault();
                    showError('Quantities must be whole numbers from 1 to 999.');
                    input.focus();
                    return;
                }
            }
            error.hidden = true;
        });
    }

    if (removeForm) {
        removeForm.addEventListener('submit', function (event) {
            const selected = document.querySelectorAll('input[form="cart-remove-form"][name="remove[]"]:checked');
            if (selected.length === 0) {
                event.preventDefault();
                showError('Select at least one item to remove.');
                return;
            }
            error.hidden = true;
        });
    }
});
