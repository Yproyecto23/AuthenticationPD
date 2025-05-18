document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.register-form');
    form.addEventListener('submit', function (e) {
        const username = form.username.value.trim();
        const email = form.email.value.trim();
        const password = form.password.value;
        const passwordConfirm = form.password_confirm.value;

        const maxPasswordLength = 8;
        const forbiddenChars = /[<>"']/;

        let errors = [];

        if (!username) {
            errors.push('El usuario es obligatorio.');
        }

        // Email simple regex para validar formato
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errors.push('El correo electrónico no es válido.');
        }

        if (password.length > maxPasswordLength) {
            errors.push('La contraseña debe tener máximo 8 caracteres.');
        }

        if (forbiddenChars.test(password)) {
            errors.push('La contraseña contiene caracteres no permitidos (<, >, ", \').');
        }

        if (password !== passwordConfirm) {
            errors.push('Las contraseñas no coinciden.');
        }

        if (errors.length > 0) {
            e.preventDefault();

            // Mostrar errores en pantalla (por ejemplo, alert o crear div)
            alert(errors.join('\n'));
        }
    });
});
