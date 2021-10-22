<?php 

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $token;

    public function  __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    public function enviarConfirmacion(){
        
        // Crear un objeto de mail
        $mail = new PHPMailer(true);

            // Configurar SMTP
            $mail->isSMTP();                     //Indicamos que vamos a usar SMTP
            $mail->Host = 'smtp.gmail.com';    //Agregar el host
            $mail->SMTPAuth = true;
            $mail->Username = 'javier.bz.gt@gmail.com';
            $mail->Password = 'vyoxptfdbdymayue';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;


            // Configurar el contenido del email

            $mail->setFrom('cuentas@salon.com');

            $mail->addAddress($this->email, $this->nombre);


            $mail->Subject ='Confirma tu cuenta';

            // Habilitar HTML 
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            // Definir el contendio
            $contenido = '<html>';
            $contenido.= '<p><strong>Hola' . $this->nombre . '</strong> has creado tu cuenta en App salon, solo debes confirmarla presionando el siguiente enlace </p>';
            $contenido.= '<p>Preciona aquí <a href="http://localhost:3000/confirmar-cuenta?token='. $this->token .'" >Confirmar Cuenta</a></p>';
            $contenido.= '<p>Si tu no solicitaste esta cuenta, ignora el mensaje</p>';
            $contenido.= '</html>';

            $mail->Body = $contenido;

        $mail->Subject = 'Confirma tu cuenta';
        if(!$mail->Send()) {
          } else {
            echo "Enviado!";
          }

    }

    public function enviarInstrucciones(){
        // Crear un objeto de mail
        $mail = new PHPMailer(true);

        // Configurar SMTP
        $mail->isSMTP();                     //Indicamos que vamos a usar SMTP
        $mail->Host = 'smtp.gmail.com';    //Agregar el host
        $mail->SMTPAuth = true;
        $mail->Username = 'javier.bz.gt@gmail.com';
        $mail->Password = 'vyoxptfdbdymayue';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Configurar el contenido del email

        $mail->setFrom('cuentas@salon.com');

        $mail->addAddress($this->email, $this->nombre);


        $mail->Subject ='Restablece tu password';

          // Habilitar HTML 
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

          // Definir el contendio
        $contenido = '<html>';
        $contenido.= '<p><strong>Hola' . $this->nombre . '</strong> Has solicitado restablecer tu password </p>';
        $contenido.= '<p>Preciona aquí <a href="http://localhost:3000/recuperar?token='. $this->token .'" >Restablecer Password</a></p>';
        $contenido.= '<p>Si tu no solicitaste esta cuenta, ignora el mensaje</p>';
        $contenido.= '</html>';

        $mail->Body = $contenido;

        if(!$mail->Send()) {
        } else {
            echo "Enviado!";
        }
    }
}