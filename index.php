<?php
/*
* Author: TuNombre (puedes reemplazarlo)
* Fecha: 2025-05-18
* Version: 1.0
* Descripción: Punto de entrada principal de la aplicación. 
* Define las rutas utilizando un router personalizado, y conecta con los controladores, vistas y modelos.
*/

// Importación de clases principales del núcleo
require_once __DIR__ . '/app/resources/php/core/Router.php';
require_once __DIR__ . '/app/resources/php/core/View.php';
require_once __DIR__ . '/app/resources/php/core/Database.php';
require_once __DIR__ . '/app/resources/php/class/User.php';
require_once __DIR__ . '/app/resources/php/controller/LogoutController.php';

// Instancia del router
$router = new Router();

/*
* Ruta GET: /
* Descripción: Muestra la vista de login inicial.
*/
$router->get('/', function () {
    View::render('login');
});

/*
* Ruta POST: /login
* Descripción: Procesa los datos enviados desde el formulario de login.
*/
$router->post('/login', function () {
    require_once __DIR__ . '/app/resources/php/controller/LoginController.php';
    $controller = new LoginController();
    $controller->login();
});

/*
* Ruta GET: /dashboard
* Descripción: Muestra el panel de usuario si hay sesión activa.
*/
$router->get('/dashboard', function () {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: /");
        exit();
    }
    View::render('dashboard', ['username' => $_SESSION['username']]);
});

/*
* Ruta GET: /register
* Descripción: Muestra el formulario de registro.
*/
$router->get('/register', function() {
    View::render('register', ['error_block' => '']);
});

/*
* Ruta POST: /register
* Descripción: Procesa el formulario de registro para crear un nuevo usuario.
*/
$router->post('/register', function() {
    require_once __DIR__ . '/app/resources/php/controller/RegisterController.php';
    $controller = new RegisterController();
    $controller->register();
});

/*
* Ruta POST: /logout
* Descripción: Cierra la sesión de usuario actual, validando CSRF.
*/
$router->post('/logout', function() {
    require_once __DIR__ . '/app/resources/php/controller/LogoutController.php';
    $controller = new LogoutController();
    $controller->logout();
});

/*
* Descripción: Ejecuta el despachador del router, resolviendo la ruta actual.
*/
$router->dispatch();
