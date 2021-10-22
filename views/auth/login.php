<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina" >Inicia sesión con tus datos</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php" 
?>

<form method="POST" action="/" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            class="input"
            type="email"
            id="email"
            name="email"
            placeholder="Tu Email"
            value="<?php echo $auth->email; ?>"
            
        >
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Tu password"            
        >
    </div>
    <input type="submit" class="boton" value="Iniciar Sesión &#8640;">
</form>
<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una cuenta</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>