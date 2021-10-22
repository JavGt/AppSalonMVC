<?php
namespace Model;

use MVC\Router;

class Usuario extends ActiveRecord{
    // Base de datos
    protected static $tabla = 'usuarios';
    
    protected static $columnasDB = [
        'id', 
        'nombre', 
        'apellido', 
        'token', 
        'confirmado',
        'admin',
        'telefono',
        'email',
        'password'
    ];

    public $id;
    public $nombre;
    public $apellido;
    public $token;
    public $confirmado;
    public $admin;
    public $telefono;
    public $email;
    public $password;

    public function  __construct( $args = [] ) {
        $this->id = $args['id'] ?? NULL;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->token = $args['token'] ?? NULL;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->admin = $args['admin'] ?? 0;
        $this->telefono = $args['telefono'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
    }

    // Mensajes de validación para la creacion de una cuenta
    public function validarNuevaCuenta(){
        if (!$this->nombre) {
            self::$alertas['error'][] = 'Nombre Obligatorio ';
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = 'Apellido Obligatorio ';
        }
        if (!$this->telefono) {
            self::$alertas['error'][] = 'Teléfono Obligatorio ';
        }else{
            if (!preg_match('/[0-9 ]{10}/', $this->telefono)) {
                // Expresion regular
                self::$alertas['error'][] = 'El Número telefonico no es valido';
                $this->telefono = '';
            }
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'Email Obligatorio ';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'Password Obligatorio ';
        }else{
            if (strlen($this->password) < 6 ) {
                self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres ';
            }else{
                if (preg_match('/[0-9 ]/', $this->password) && !preg_match('/[a-z ]/', $this->password) ) {
                    self::$alertas['error'][] = 'El password debe contener al menos una letra';
                }
                if (!preg_match('/[0-9 ]/', $this->password) && preg_match('/[a-z ]/', $this->password) ) {
                    self::$alertas['error'][] = 'El password debe contener al menos una Numero';
                }
            }
        }
        
        return self::$alertas;    
    }
    
    public function validarEmail(){
        if (!$this->email) {
            self::$alertas['error'][] = 'Email Obligatorio ';
        }    
        return self::$alertas;

    }

    public function validarPassword(){
        if (!$this->password) {
            self::$alertas['error'][] = 'Password Obligatorio ';
        }else{
            if (strlen($this->password) < 6 ) {
                self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres ';
            }else{
                if (preg_match('/[0-9 ]/', $this->password) && !preg_match('/[a-z ]/', $this->password) ) {
                    self::$alertas['error'][] = 'El password debe contener al menos una letra';
                }
                if (!preg_match('/[0-9 ]/', $this->password) && preg_match('/[a-z ]/', $this->password) ) {
                    self::$alertas['error'][] = 'El password debe contener al menos una Numero';
                }
            }
        }
        return self::$alertas;    
    }

    public function existeUsuario() {
        $query = "SELECT * FROM ". self::$tabla  . " WHERE email ='". $this->email ."' LIMIT 1";
        $resultado = self::$db->query($query);

        if ($resultado->num_rows) {
            self::$alertas['error'][] = 'El correo ya ha sido utilizado';
        }
        return $resultado;
    }
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    public function crearToken(){
        $this->token = uniqid();

    }

    public function validarLogin(){
        if (!$this->email) {
            self::$alertas['error'][] = 'El correo electronico es obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }

        return self::$alertas;
    }

    public function comprobarPasswordAndVerificado($password){
        $resultado = password_verify($password, $this->password);

        if ($resultado) {

            if (!$this->confirmado) {
                self::$alertas['error'][] = 'El usuario no esta autenticado';
            } else {
                return true;
            }
        } else {
            self::$alertas['error'][] = 'Password incorrecto';
        }
    }
}