<?php 
namespace Controllers;

use MVC\Router;

class CitaController{
    public static function index(Router $router){
        // session_start();
        isAuth();

        $fechaActual = date("Y-m-d", strtotime('+1 day'));

        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id'],
            'fechaActual' => $fechaActual
        ]);
    }
}
?>