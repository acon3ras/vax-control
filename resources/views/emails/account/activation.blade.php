<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f8fafc; color: #334155; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { width: 64px; height: 64px; border-radius: 12px; }
        .title { font-size: 24px; font-weight: bold; color: #0f172a; margin-top: 10px; }
        .text { font-size: 16px; line-height: 1.6; margin-bottom: 24px; }
        .button-container { text-align: center; margin: 30px 0; }
        .button { background-color: #0ea5e9; color: white; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 16px; display: inline-block; }
        .footer { font-size: 12px; color: #94a3b8; text-align: center; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">Bienvenido a Control de Vacunas</div>
        </div>
        
        <p class="text">Hola <strong>{{ $user->name }}</strong>,</p>
        
        <p class="text">Se ha creado una cuenta para usted en el sistema de Control de Vacunas del Hospital de Puerto Aysén.</p>
        
        <p class="text">Para activar su cuenta y configurar su contraseña, por favor haga clic en el siguiente botón:</p>
        
        <div class="button-container">
            <a href="{{ $url }}" class="button">Activar Cuenta</a>
        </div>
        
        <p class="text">Si el botón no funciona, copie y pegue el siguiente enlace en su navegador:</p>
        <p class="footer">{{ $url }}</p>
        
        <div class="footer">
            <p>Este correo fue enviado automáticamente. Por favor no responda a este mensaje.<br>
            {{ date('Y') }} Hospital de Puerto Aysén.</p>
        </div>
    </div>
</body>
</html>
