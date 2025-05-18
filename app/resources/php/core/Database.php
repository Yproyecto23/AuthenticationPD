<?php
/*
* Author: TuNombre
* Fecha: 2025-05-18
* Versión: 1.0
* Descripción: Clase responsable de gestionar la conexión a la base de datos utilizando PDO. 
*              Lee la configuración desde un archivo externo y crea una conexión única reutilizable.
*/

class Database {
    // Instancia estática para mantener la conexión única
    private static ?PDO $connection = null;

    /**
     * Establece y devuelve una conexión PDO a la base de datos.
     *
     * @return PDO Objeto de conexión PDO.
     */
    public static function connect(): PDO {
        // Si no existe una conexión previa, la crea
        if (self::$connection === null) {
            $configPath = __DIR__ . '/../../../config/db_config.txt';

            if (!file_exists($configPath)) {
                die('Archivo de configuración de base de datos no encontrado.');
            }

            // Lee parámetros desde archivo INI
            $params = parse_ini_file($configPath);
            $host = $params['host'] ?? 'localhost';
            $dbname = $params['dbname'] ?? 'test';
            $user = $params['user'] ?? 'root';
            $password = $params['password'] ?? '';

            try {
                // Establece la conexión PDO
                self::$connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Error al conectar a la base de datos: ' . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
?>