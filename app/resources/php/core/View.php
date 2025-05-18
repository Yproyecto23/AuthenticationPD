<?php
/*
* Author: TuNombre
* Fecha: 2025-05-18
* Versión: 1.0
* Descripción: Motor de plantillas simple y manual que permite renderizar vistas HTML 
* sustituyendo variables y manejando bloques condicionales como {{ if }} y {{ else }}.
*/

class View {

    /*
    * Método: render
    * Descripción: Renderiza una vista HTML reemplazando variables y manejando bloques condicionales.
    * Parámetros:
    *   - string $template: Nombre del archivo de plantilla (sin extensión .html).
    *   - array $data: Datos asociativos para sustituir variables dentro de la vista.
    * Retorno: void (imprime directamente el HTML renderizado).
    */
    public static function render(string $template, array $data = []): void {
        
        // Iniciar sesión si aún no está iniciada (necesario para CSRF)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Generar token CSRF si no existe y agregarlo a los datos
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $data['csrf_token'] = $_SESSION['csrf_token'];

        // Ruta absoluta al archivo de plantilla HTML
        $templatePath = __DIR__ . '/../../views/' . $template . '.html';

        // Verificar existencia de la plantilla
        if (!file_exists($templatePath)) {
            http_response_code(500);
            die("La plantilla '$template' no existe.");
        }

        // Cargar contenido del archivo HTML
        $html = file_get_contents($templatePath);

        /*
        * Procesar bloques condicionales personalizados:
        * Sintaxis: 
        *   {{ if key }} contenido si existe {{ else }} contenido alternativo {{ endif }}
        */
        $html = preg_replace_callback('/{{ if (\w+) }}(.*?)({{ else }}(.*?))?{{ endif }}/s', function ($matches) use ($data) {
            $key = $matches[1];
            $ifBlock = $matches[2];
            $elseBlock = isset($matches[4]) ? $matches[4] : '';
            return !empty($data[$key]) ? $ifBlock : $elseBlock;
        }, $html);

        // Reemplazar variables simples {{ key }}
        foreach ($data as $key => $value) {
            $html = str_replace('{{ ' . $key . ' }}', htmlspecialchars((string)$value), $html);
        }

        // Imprimir el HTML procesado
        echo $html;
    }
}
?>