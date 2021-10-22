<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina" >Restablece tu password escribiendo tu Email</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php" 
?>

<form action="/olvide" class="formulario" method="POST" >
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Tu Email" name="email" >
    </div>
    <input type="submit" class="boton" value="Enviar Instrucciones">
</form>
<div class="acciones">
    <a href="/">Inicia Sesión</a>
    <a href="/crear-cuenta">Crear una cuenta</a>
</div>