<?php
session_start();

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$siteKey = $_ENV['RECAPTCHA_V2_SITE'] ?? '';

$captcha = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5);
$_SESSION['captcha'] = $captcha;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registrar Proyecto</title>


  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
  <div class="contenedor">
    <div class="col-izquierda">
      <div class="contenido">
        <img src="https://agenciacristal.com/wp-content/uploads/2023/06/logo-blanco.png" alt="Agencia Cristal" />
        <h2>Cuéntanos sobre tu proyecto</h2>
        <p>Transformamos tu idea en software innovador y de alto impacto</p>
        <small>Una vez enviado, nuestro líder de proyectos recibirá una notificación con tu propuesta para evaluarla y darte una respuesta ágil</small>
      </div>
    </div>

    <div class="col-derecha">
      <h3>Registrar Proyecto</h3>

      <form action="procesar.php" method="POST" novalidate>
        <div class="input-grupo">
          <i class="fa fa-lightbulb"></i>
          <input type="text" name="nombre_proyecto" placeholder="Nombre del proyecto" required>
        </div>

        <div class="input-grupo">
          <i class="fa fa-clock"></i>
          <input type="text" name="plazo" placeholder="Plazo estimado (Ej: 3 meses)" required>
        </div>

        <div class="input-grupo">
          <i class="fa fa-align-left"></i>
          <textarea name="descripcion" placeholder="Descripción del proyecto" required></textarea>
        </div>

        <div class="input-grupo">
          <i class="fa fa-dollar-sign"></i>
          <input type="number" name="presupuesto" placeholder="Presupuesto estimado ($)" required>
        </div>

        <div class="categorias">
          <label><input type="checkbox" name="categoria[]" value="Web"> <i class="fa fa-globe"></i> Web</label>
          <label><input type="checkbox" name="categoria[]" value="Moviles"> <i class="fa fa-mobile"></i> Móviles</label>
          <label><input type="checkbox" name="categoria[]" value="IA"> <i class="fa fa-robot"></i> IA</label>
          <label><input type="checkbox" name="categoria[]" value="Escritorio"> <i class="fa fa-desktop"></i> Escritorio</label>
        </div>

        <div class="input-grupo">
          <i class="fa fa-user"></i>
          <input type="text" name="nombre" placeholder="Su nombre" required>
        </div>

        <div class="input-grupo">
          <i class="fa fa-whatsapp"></i>
          <input type="text" name="whatsapp" placeholder="Su Whatsapp" required>
        </div>

        <div class="input-grupo">
          <i class="fa fa-envelope"></i>
          <input type="email" name="correo" placeholder="Su Correo" required>
        </div>

        <div class="captcha-box">
          <span class="captcha-text"><?php echo htmlspecialchars($_SESSION['captcha'] ?? ''); ?></span>
          <input type="text" name="captcha" placeholder="Escribe el código de arriba" required>
        </div>

        <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($siteKey); ?>"></div>

        <button type="submit" class="btn-enviar">
          <i class="fa fa-paper-plane"></i> Enviar Proyecto
        </button>
      </form>
    </div>
  </div>
</body>
</html>
