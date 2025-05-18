<?php
/*
* Author: TuNombre
* Fecha: 2025-05-18
* Versión: 1.0
* Descripción: Función auxiliar para verificar si un usuario ha iniciado sesión en el sistema.
*/

/**
 * Verifica si el usuario está autenticado (sesión iniciada).
 *
 * @return bool Retorna true si el usuario está autenticado, false en caso contrario.
 */
function checkAuth(): bool {
    if (session_status() === PHP_SESSION_NONE) 
    {
        session_start();
    }
    return isset($_SESSION['user_id']);
}
?>