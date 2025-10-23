<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperar contraseña</title>
<style>
    body { font-family: Arial, sans-serif; background: #f8f8f8; padding: 20px; color: #333; }
    .container { background: #fff; padding: 20px; max-width: 500px; margin: 0 auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    h2 { color: #0f3a2e; }
    a.button { background: #1c755d; color: #fff; text-decoration: none; padding: 12px 20px; border-radius: 5px; display: inline-block; margin-top: 20px; }
    p.footer { font-size: 12px; color: #777; margin-top: 20px; }
</style>
</head>
<body>
<div class="container">
    <h2>Solicitud para restablecer tu contraseña</h2>
    <p>Hola, recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>Chaxras</strong>.</p>
    <p>Para continuar, haz clic en el siguiente botón:</p>
    <a href="{{LINK}}" class="button">Restablecer contraseña</a>
    <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
    <p class="footer">Este enlace expirará en 1 hora por motivos de seguridad.</p>
</div>
</body>
</html>
