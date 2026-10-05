const form = document.getElementById('register-form');

if (form) {
    form.addEventListener('submit', function (event) {
        form.querySelectorAll('.field-error').forEach(el => el.remove());

        const rules = [
            ['name', value => value.length >= 2, 'Enter your full name.'],
            ['email', value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value), 'Enter a valid email.'],
            ['password', value => /^(?=.*\d).{8,}$/.test(value), 'Use at least 8 characters and one digit.'],
            ['country', value => value !== '', 'Select a country.'],
            ['city', value => value.length > 0, 'Enter your city.'],
            ['contact', value => /^[0-9+\-\s]{7,15}$/.test(value), 'Enter a valid contact number.']
        ];

        for (const [fieldName, isValid, message] of rules) {
            const input = form.elements[fieldName];
            if (isValid(input.value.trim())) continue;

            event.preventDefault();
            const error = document.createElement('span');
            error.className = 'field-error';
            error.textContent = message;
            input.insertAdjacentElement('afterend', error);
        }
    });
}

const loginForm = document.getElementById('login-form');

if (loginForm) {
    loginForm.addEventListener('submit', function (event) {
        const email = loginForm.elements['email'].value.trim();
        const password = loginForm.elements['password'].value;

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) || password === '') {
            event.preventDefault();
            alert('Enter a valid email and password.');
        }
    });
}

document.querySelectorAll('.catalog-form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        const input = form.querySelector('[data-catalog-name]');
        const error = form.querySelector('.field-error');
        const name = input.value.trim();

        error.textContent = '';

        if (!/^.{2,100}$/u.test(name)) {
            event.preventDefault();
            error.textContent = 'Enter a name between 2 and 100 characters.';
            input.focus();
        }
    });
});