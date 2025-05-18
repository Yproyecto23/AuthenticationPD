<?php
/*
* Author: TuNombre
* Fecha: 2025-05-18
* Versión: 1.0
* Descripción: Clase Router para definir rutas y manejar el enrutamiento básico de peticiones
* GET y POST en una aplicación PHP sin framework.
*/

class Router {
    /**
     * @var array $routes Almacena las rutas definidas organizadas por método HTTP.
     */
    private array $routes = [];

    /*
    * Método: get
    * Descripción: Registra una ruta de tipo GET.
    * Parámetros:
    *   - string $path: Ruta de la URL (ej: '/login').
    *   - callable $callback: Función a ejecutar cuando se accede a la ruta.
    * Retorno: void
    */
    public function get(string $path, callable $callback): void {
        $this->routes['GET'][$path] = $callback;
    }

    /*
    * Método: post
    * Descripción: Registra una ruta de tipo POST.
    * Parámetros:
    *   - string $path: Ruta de la URL (ej: '/login').
    *   - callable $callback: Función a ejecutar cuando se accede a la ruta.
    * Retorno: void
    */
    public function post(string $path, callable $callback): void {
        $this->routes['POST'][$path] = $callback;
    }

    /*
    * Método: dispatch
    * Descripción: Procesa la petición actual y ejecuta el callback correspondiente a la ruta.
    * Si no existe la ruta, muestra un error 404.
    * Retorno: void
    */
    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD']; // Método HTTP (GET o POST)
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Ruta solicitada
        $scriptName = dirname($_SERVER['SCRIPT_NAME']); // Directorio del script
        $path = '/' . trim(str_replace($scriptName, '', $uri), '/'); // Ruta relativa

        if ($path === '') $path = '/';

        if (isset($this->routes[$method][$path])) {
            call_user_func($this->routes[$method][$path]); // Ejecutar ruta encontrada
        } else {
            http_response_code(404);
            echo "404 - Página no encontrada";
        }
    }
}
?>