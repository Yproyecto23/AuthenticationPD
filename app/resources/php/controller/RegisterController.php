<?php
/*
* Author: TuNombre
* Fecha: 2025-05-18
* Versión: 1.1
* Descripción: Controlador encargado del registro de usuarios. 
*              Valida los datos del formulario, gestiona la seguridad (CSRF y sanitización), 
*              verifica duplicados, crea un objeto User y almacena al nuevo usuario en la base de datos.
*/

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../class/User.php';
require_once __DIR__ . '/../core/View.php';

class RegisterController {
    /**
     * Procesa el formulario de registro.
     */
    public function register(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // Validaciones básicas
        if (!$username || !$email || !$password || !$passwordConfirm) {
            $this->renderWithError('Todos los campos son obligatorios.');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->renderWithError('Correo electrónico inválido.');
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->renderWithError('Las contraseñas no coinciden.');
            return;
        }

        if (strlen($password) < 6) {
            $this->renderWithError('La contraseña debe tener al menos 6 caracteres.');
            return;
        }

        if (strlen($password) > 8) {
            $this->renderWithError('La contraseña debe tener máximo 8 caracteres.');
            return;
        }

        // Revisión de caracteres no permitidos
        if (preg_match('/[<>"\']/', $password)) {
            $this->renderWithError('La contraseña contiene caracteres no permitidos (<, >, ", \').');
            return;
        }

        // Validación de token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->renderWithError('Token CSRF inválido.');
            return;
        }

        try {
            $db = Database::connect();

            // Verificar existencia previa del usuario
            $stmt = $db->prepare("SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->fetch()) {
                $this->renderWithError('El usuario o correo ya está registrado.');
                return;
            }

            // Crear objeto User
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($email);

            // Hashear la contraseña y setearla
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $user->setPasswordHash($passwordHash);

            // Insertar en base de datos
            $insertStmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $insertStmt->bindParam(':username', $user->getUsername());
            $insertStmt->bindParam(':email', $user->getEmail());
            $insertStmt->bindParam(':password', $user->getPasswordHash());
            $insertStmt->execute();

            // Redirigir al login
            header('Location: /');
            exit();

        } catch (PDOException $e) {
            $this->renderWithError('Error en la base de datos: ' . $e->getMessage());
        }
    }

    /**
     * Renderiza la vista de registro con un mensaje de error.
     *
     * @param string $errorMessage El mensaje de error a mostrar.
     */
    private function renderWithError(string $errorMessage): void {
        View::render('register', ['error_block' => $errorMessage]);
    }
}
?>