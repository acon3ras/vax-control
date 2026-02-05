<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f1f5f9; -webkit-font-smoothing: antialiased;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f1f5f9; padding: 40px 0;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 40px 0; background: linear-gradient(135deg, #0f172a 0%, #0d4a6e 100%);">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">
                                Control de <span style="color: #38bdf8;">Vacunas</span>
                            </h1>
                            <p style="color: #94a3b8; margin: 5px 0 0 0; font-size: 14px; font-weight: 500;">Hospital de Puerto Aysén</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #0f172a; margin: 0 0 20px 0; font-size: 20px; font-weight: 700;">Recuperación de Contraseña</h2>
                            
                            <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 0 0 24px 0;">
                                Hola,
                            </p>
                            <p style="color: #475569; font-size: 16px; line-height: 1.6; margin: 0 0 32px 0;">
                                Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>VaxControl</strong>. Si fuiste tú, simplemente haz clic en el siguiente botón para crear una nueva contraseña.
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}" 
                                           style="display: inline-block; background-color: #059669; color: #ffffff; font-size: 16px; font-weight: 700; text-decoration: none; padding: 14px 32px; border-radius: 12px; transition: background-color 0.3s; box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.2);">
                                            Restablecer Contraseña
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin: 32px 0 0 0; text-align: center;">
                                Este enlace expirará automáticamente en <strong style="color: #475569;">60 minutos</strong>.
                            </p>

                            <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="100%" style="margin-top: 32px; border-top: 1px solid #e2e8f0; padding-top: 32px;">
                                <tr>
                                    <td>
                                        <p style="color: #94a3b8; font-size: 13px; line-height: 1.6; margin: 0;">
                                            Si no solicitaste este cambio, puedes ignorar este correo de forma segura. Tu contraseña actual seguirá siendo la misma.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 24px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                                &copy; {{ date('Y') }} Hospital de Puerto Aysén. Todos los derechos reservados.
                            </p>
                            <p style="color: #cbd5e1; font-size: 11px; margin: 8px 0 0 0;">
                                Este es un correo automatizado del sistema de Control de Vacunas. Por favor no respondas a este mensaje.
                            </p>
                        </td>
                    </tr>
                </table>
                
                <!-- Helper for plain text link -->
                <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin-top: 20px;">
                    <tr>
                        <td align="center" style="padding: 0 20px;">
                            <p style="color: #94a3b8; font-size: 12px; line-height: 1.5; margin: 0;">
                                Si tienes problemas con el botón, copia y pega el siguiente enlace en tu navegador:<br>
                                <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}" style="color: #0ea5e9; text-decoration: none; word-break: break-all;">
                                    {{ route('password.reset', ['token' => $token, 'email' => $email]) }}
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>
</html>
