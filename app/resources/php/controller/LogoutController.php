<?php
/*
* Author: TuNombre
* Fecha: 2025-05-18
* Versión: 1.0
* Descripción: Controlador encargado de manejar el cierre de sesión (logout) de los usuarios.
*              Valida el método y token CSRF, destruye la sesión y redirige al inicio.
*/

require_once __DIR__ . '/../core/View.php';

class LogoutController
{
    /**
     * Cierra la sesión del usuario de forma segura.
     */
    public function logout(): void
    {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Validar que la petición sea por POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Método no permitido
            echo "Método no permitido.";
            exit();
        }

        // Validar CSRF token
        if (
            !isset($_POST['csrf_token'], $_SESSION['csrf_token']) || 
            $_POST['csrf_token'] !== $_SESSION['csrf_token']
        ) {
            http_response_code(403); // Prohibido
            echo "Token CSRF inválido.";
            exit();
        }

        // Eliminar variables de sesión
        $_SESSION = [];

        // Eliminar cookie de sesión si aplica
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destruir sesión completamente
        session_destroy();

        // Redirigir al inicio o login
        header('Location: /');
        exit();
    }
}
?>