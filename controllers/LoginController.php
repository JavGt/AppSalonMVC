<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    public static function login(Router $router ){

        if($_SESSION){
            if ($_SESSION['admin']) {
                header('Location: /admin');
            }else{
                header('Location: /cita');
            }
        }
        $alertas =[];
        $auth = new Usuario;


        if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if ( empty($alertas) ) {
                // Comprobar que exita el usuario
                $usuario = Usuario::where("email", $auth->email);

                // Verificar el password
                if ($usuario) {

                    if ( $usuario->comprobarPasswordAndVerificado($auth->password) ) {

                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // redirecionamiento
                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? NULL;
                            header('Location: /admin');
                        } else{
                            header('Location: /cita');
                        }


                        debuguear($_SESSION);
                    }

                }else{
                    Usuario::setAlerta('error', 'Usuario no valido');
                    
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login',[
            'alertas'=> $alertas,
            'auth' => $auth
        ]);
    }

    public static function logout( ){
        // session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router){

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where("email", $auth->email);

                if ($usuario && $usuario->confirmado === "1") {

                    // Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    // enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    
                    // Alerta exito
                    Usuario::setAlerta('exito', 'Revisa tu email');

                } else if (!$usuario) {
                    Usuario::setAlerta('error', 'El usuario no existe');

                } else if (!$usuario->confirmado == "1") {
                    Usuario::setAlerta('error', 'El usuario no esta confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();  

        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router){

        $alertas = [];
        $token = s($_GET['token']);

        // Buscar usuario por su token

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {

            header('Location:/error');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();        

            if (empty($alertas)) {
                $usuario->password = NULL;
                $usuario->password = $password->password ;
                $usuario->hashPassword();
                $usuario->token = '';

                $resultado = $usuario->guardar();
                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/recuperar-password',[
            'alertas' => $alertas
        ]);
    }

    public static function crear(Router $router){
        $usuario = new Usuario();

        // Alertas vacias
        $alertas = [];

        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                }else{
                    // hashear el password
                    $usuario->hashPassword();
                    // Generar el token
                    $usuario->crearToken();
                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }

                }
            }
        }

        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function confirmar(Router $router){

        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {

            header('Location:/error');
        } else {

            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta confirmada');
        }

        // Obtener alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta',[
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje',[]);
    }

    public static function error(Router $router){
        $router->render('templates/404',[]);
    }
}