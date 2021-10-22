<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/build/css/app.css">
    <link rel="icon" type="image/svg+xml" href="build/img/handlebarsdotjs.svg" sizes="any">

</head>
<body>

    <div class="contenedor-app">
        <div class="imagen">
        </div>
        
        <div class="app">
            <?php echo $contenido; ?>
        </div>
    </div>
    <?php 
        echo $script ?? '';
     ?>
</body>
</html>