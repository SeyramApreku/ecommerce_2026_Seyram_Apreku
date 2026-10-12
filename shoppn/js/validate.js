const form = document.getElementById('register-form');

if (form) {
    form.addEventListener('submit', function (event) {
        form.querySelectorAll('.field-error').forEach(el => el.remove());

        const rules = [
            ['name', value => value.length >= 2, 'Enter your full name.'],
            ['email', value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value), 'Enter a valid email.'],
            ['password',
                value => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9\s])\S{8,}$/.test(value),
                'Use at least 8 characters, including uppercase, lowercase, a number, and a special character.'],
            ['country', value => value !== '', 'Select a country.'],
            ['city', value => value.length > 0, 'Enter your city.'],
            ['contact', value => /^[0-9+\-\s]{7,15}$/.test(value), 'Enter a valid contact number.']
        ];

        for (const [fieldName, isValid, message] of rules) {
            const input = form.elements[fieldName];
            const value = fieldName === 'password'
                ? input.value
                : input.value.trim();

            if (isValid(value)) continue;

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


const productForm = document.getElementById('product-form');

if (productForm) {
    productForm.addEventListener('submit', function (event) {
        const error = document.getElementById('product-form-error');
        const title = productForm.elements['product_title'].value.trim();
        const category = productForm.elements['product_cat'].value;
        const brand = productForm.elements['product_brand'].value;
        const price = productForm.elements['product_price'].value.trim();
        const description = productForm.elements['product_desc'].value.trim();
        const keywords = productForm.elements['product_keywords'].value.trim();
        const imageInput = productForm.elements['product_image'];
        const image = imageInput.files[0];

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp'
        ];

        error.textContent = '';

        const messages = [];

        if (title.length < 2 || title.length > 200) {
            messages.push('Title must be 2–200 characters.');
        }

        if (!/^[1-9]\d*$/.test(category) || !/^[1-9]\d*$/.test(brand)) {
            messages.push('Choose a category and brand.');
        }

        if (
            !/^\d{1,8}(?:\.\d{1,2})?$/.test(price) ||
            Number(price) <= 0
        ) {
            messages.push('Enter a positive price with up to two decimal places.');
        }

        if (description.length > 500 || keywords.length > 100) {
            messages.push('Description must not exceed 500 characters and keywords 100.');
        }

        if (imageInput.required && !image) {
            messages.push('Choose a product image.');
        }

        if (image) {
            if (!allowedTypes.includes(image.type)) {
                messages.push('Choose a JPG, PNG, GIF, or WebP image.');
            }

            if (image.size === 0 || image.size > 2 * 1024 * 1024) {
                messages.push('Image must be no larger than 2 MB.');
            }
        }

        if (messages.length > 0) {
            event.preventDefault();
            error.textContent = messages.join(' ');
        }
    });
}