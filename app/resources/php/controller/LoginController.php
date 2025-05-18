<?php
/*
* Author: TuNombre
* Fecha: 2025-05-18
* Versión: 1.0
* Descripción: Controlador encargado de manejar la lógica de inicio de sesión.
*              Implementa validaciones, límite de intentos, verificación de usuario,
*              manejo de sesión y protección con token CSRF.
*/

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../class/User.php';
require_once __DIR__ . '/../core/View.php';

class LoginController {
    /**
     * Procesa el intento de login, valida credenciales y maneja intentos fallidos.
     */
    public function login(): void {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Configuración para límite de intentos
        $maxAttempts = 5;
        $lockoutTime = 150; // 5 minutos en segundos

        // Inicializar contadores si no existen
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = time();
        }

        // Verificar si está bloqueado por demasiados intentos fallidos
        if ($_SESSION['login_attempts'] >= $maxAttempts) {
            $timeSinceLastAttempt = time() - $_SESSION['last_attempt_time'];
            if ($timeSinceLastAttempt < $lockoutTime) {
                $remaining = $lockoutTime - $timeSinceLastAttempt;
                $this->renderWithError("Demasiados intentos fallidos. Intenta de nuevo en $remaining segundos.");
                return;
            } else {
                // Reiniciar contador tras pasar tiempo de bloqueo
                $_SESSION['login_attempts'] = 0;
            }
        }

        // Obtener datos del formulario
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validar campos obligatorios
        if (empty($username) || empty($password)) {
            $this->renderWithError('Todos los campos son obligatorios.');
            return;
        }

        // Validar token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->renderWithError('Token CSRF inválido.');
            return;
        }

        try {
            $db = Database::connect();

            // Consultar usuario por nombre de usuario
            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar contraseña
            if ($userRow && password_verify($password, $userRow['password'])) {
                // Login exitoso: resetear contador de intentos
                $_SESSION['login_attempts'] = 0;

                // Crear instancia User
                $user = new User(
                    $userRow['id'],
                    $userRow['username'],
                    $userRow['email'],
                    $userRow['password']
                );

                // Guardar datos en sesión
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['username'] = $user->getUsername();

                // Redirigir al dashboard
                header('Location: /dashboard');
                exit();
            } else {
                // Login fallido: incrementar contador de intentos
                $_SESSION['login_attempts']++;
                $_SESSION['last_attempt_time'] = time();

                $this->renderWithError('Credenciales inválidas.');
                return;
            }
        } catch (PDOException $e) {
            $this->renderWithError('Error de conexión: ' . $e->getMessage());
        }
    }

    /**
     * Renderiza la vista de login con un mensaje de error.
     *
     * @param string $errorMessage Mensaje de error a mostrar.
     */
    private function renderWithError(string $errorMessage): void {
        View::render('login', ['error_block' => $errorMessage]);
    }
}
?>