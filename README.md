# AuthenticationPD

# Proyecto de Gestión de Usuarios - Registro y Login

## Descripción

Este proyecto implementa un sistema básico de registro e inicio de sesión para usuarios, con enfoque en la seguridad, validación y buena arquitectura de código. Incluye controladores, modelo de usuario, vistas HTML y estilos CSS para una experiencia limpia y responsiva.

---

## Conceptos Implementados

### Controladores (RegisterController y LoginController)

- Validación exhaustiva de entradas (campos obligatorios, formato email, contraseñas).
- Protección contra ataques CSRF mediante tokens.
- Manejo de sesiones PHP para seguridad.
- Uso de PDO con consultas preparadas para evitar inyecciones SQL.
- Manejo de errores y mensajes amigables al usuario.

### Clase User (Modelo)

- Encapsulamiento con propiedades privadas y getters/setters.
- Representación clara del usuario con separación de responsabilidades.
- Reutilización en distintos controladores.

### Vistas HTML

- Separación de presentación y lógica con plantillas dinámicas.
- Formularios accesibles con etiquetas correctamente asociadas.
- Inclusión y validación de tokens CSRF.
- Diseño responsivo para dispositivos móviles y escritorio.

### CSS

- Reset básico para consistencia entre navegadores.
- Estilos limpios, profesionales y consistentes.
- Adaptación mediante media queries para dispositivos pequeños.
- Uso de transiciones y estados hover para mejorar UX.

### Seguridad

- Almacenamiento seguro de contraseñas usando `password_hash()`.
- Validación y sanitización de entradas para evitar inyecciones.
- Protección CSRF.

---

## Flujo de Registro de Usuario

1. Usuario completa formulario y envía datos.
2. Controller valida datos y token CSRF.
3. Se verifica que usuario o email no existan en BD.
4. Se crea instancia de `User` con los datos validados.
5. La contraseña es hasheada y asignada.
6. Datos son guardados en base de datos.
7. Usuario es redirigido a la página de login.

## Tecnologías Utilizadas

- PHP (POO y PDO)
- HTML5 y CSS3 (Flexbox, media queries)
- Seguridad web básica (CSRF, hashing de contraseñas)
- MVC básico (Separación de responsabilidades)

---

## Instalación y Uso

1. Clonar este repositorio.
2. Configurar la base de datos y actualizar las credenciales en `Database.php`.
3. Asegurar que el servidor web soporte PHP 7.4+ y tenga PDO habilitado.
4. Acceder a la URL de la aplicación para registrar y loguear usuarios.

---

## Autor

TuNombre - 2025

---

## Licencia

Este proyecto está bajo la licencia MIT.

Pasos para usar la aplicación:
1.- instala visual studio code
2.- utilizar xammp y configurarlo
4.- usar el comando php -S localhost:8000
