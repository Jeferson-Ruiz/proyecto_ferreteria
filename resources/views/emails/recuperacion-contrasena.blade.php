<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recuperación de Contraseña</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 5px 5px; }
        .button { 
            background: #28a745; 
            color: white; 
            padding: 12px 25px; 
            text-decoration: none; 
            border-radius: 5px; 
            display: inline-block; 
            margin: 15px 0;
        }
        .footer { 
            margin-top: 20px; 
            padding-top: 20px; 
            border-top: 1px solid #ddd; 
            text-align: center; 
            color: #666;
        }
        .token { 
            background: #eee; 
            padding: 10px; 
            border-radius: 3px; 
            word-break: break-all; 
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Recuperación de Contraseña</h1>
        </div>
        
        <div class="content">
            <p>Hola,</p>
            
            <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>Ferretería San Miguel</strong>.</p>
            
            <p>Para continuar, haz clic en el siguiente enlace:</p>
            
            <p style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">
                    🔗 Restablecer Contraseña
                </a>
            </p>
            
            <p>O copia y pega este enlace en tu navegador:</p>
            <p class="token">{{ $resetUrl }}</p>
            
            <p><strong>⚠️ Importante:</strong> Este enlace expirará en 24 horas.</p>
            
            <p>Si no solicitaste este cambio, puedes ignorar este mensaje.</p>
            
            <p>Para cualquier duda, contacta al administrador del sistema.</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Ferretería San Miguel. Todos los derechos reservados.</p>
            <p>Este es un mensaje automático, por favor no responder.</p>
        </div>
    </div>
</body>
</html>