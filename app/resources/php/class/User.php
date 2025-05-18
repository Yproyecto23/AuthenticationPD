<?php
/*
* Author: TuNombre
* Fecha: 2025-05-18
* Versión: 1.0
* Descripción: Clase User para gestionar la información de los usuarios.
*              Incluye propiedades privadas y métodos getters/setters.
*/

class User {
    private int $id;
    private string $username;
    private string $email;
    private string $passwordHash;

    /**
     * Constructor para inicializar la instancia de User.
     *
     * @param int $id Identificador único del usuario.
     * @param string $username Nombre de usuario.
     * @param string $email Correo electrónico.
     * @param string $passwordHash Hash de la contraseña.
     */
    public function __construct($id = 0, $username = '', $email = '', $passwordHash = '') {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    // Getters

    /**
     * Obtiene el ID del usuario.
     *
     * @return int ID del usuario.
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Obtiene el nombre de usuario.
     *
     * @return string Nombre de usuario.
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * Obtiene el correo electrónico.
     *
     * @return string Correo electrónico.
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Obtiene el hash de la contraseña.
     *
     * @return string Hash de la contraseña.
     */
    public function getPasswordHash(): string {
        return $this->passwordHash;
    }

    // Setters

    /**
     * Establece el nombre de usuario.
     *
     * @param string $username Nuevo nombre de usuario.
     */
    public function setUsername(string $username): void {
        $this->username = $username;
    }

    /**
     * Establece el correo electrónico.
     *
     * @param string $email Nuevo correo electrónico.
     */
    public function setEmail(string $email): void {
        $this->email = $email;
    }

    /**
     * Establece el hash de la contraseña.
     *
     * @param string $passwordHash Nuevo hash de contraseña.
     */
    public function setPasswordHash(string $passwordHash): void {
        $this->passwordHash = $passwordHash;
    }
}
?>
