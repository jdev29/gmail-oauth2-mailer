## Gmail OAuth2 Mailer

Este es un proyecto demo de cómo enviar correos con Gmail usando Oauth2 de Google.

### **1\. Habilitar Gmail API**

1.  Ve a la Google Cloud Console.
    
2.  Crea un proyecto o selecciona uno existente.
    
3.  Habilita la **Gmail API**:
    
    *   En el menú "APIs y servicios", selecciona "Biblioteca".
        
    *   Busca "Gmail API" y haz clic en "Habilitar".
        
4.  Configura credenciales:
    
    *   En "APIs y servicios" > "Credenciales", haz clic en "Crear credenciales".
        
    *   Selecciona "ID de cliente OAuth".
        
    *   Configura una pantalla de consentimiento de OAuth:
        
        *   Llena los campos requeridos, como el nombre de la aplicación y la información de contacto.
            
    *   Selecciona el tipo de aplicación, como "Aplicación web".
        
    *   Proporciona un URI de redirección. Por ejemplo: http://localhost:8080/oauth2-callback

### **2\. Configurar variables de entorno**

En el archivo .env, agrega las credenciales de OAuth2 y la configuración de Gmail:

```
MAIL_MAILER=gmail
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_FROM_ADDRESS=user@gmail.com
MAIL_USERNAME=user@gmail.com
MAIL_FROM_NAME="Support Team"
MAIL_ENCRYPTION=tls
MAIL_SUPPORT_TEAM=user@gmail.com

GMAIL_CLIENT_ID=xxxxxxxx.apps.googleusercontent.com
GMAIL_CLIENT_SECRET=xxxxxxxx
GMAIL_ACCESS_TOKEN=xxxxxxxx
GMAIL_REFRESH_TOKEN=xxxxxxxx
GMAIL_REDIRECT_URI=http://localhost:8080/oauth2-callback
```

> **Nota:** MAIL\_PASSWORD se deja como null porque usarás OAuth2 para autenticarte.

## **3\.Excluir la verificación CSRF (Solo por temas prácticos)**
app/Http/Middleware/VerifyCsrfToken.php
```
protected $except = [
    '/mail-test', // Añadir esta línea
];
```
## **3\. Obtener Authorization URL**

Correr el proyecto usando:
```php artisan serve --host=localhost --port=8080```

Entrar a siguiente URL (verificar antes tu puerto actual puerto):
> http://localhost:8080/oauth2-callback

Esto generará una URL de autorización, copiar e ingresar al navegador, nos traerá el access token y refresh token, esos dos datos copiarlos al .env:

```
GMAIL_ACCESS_TOKEN=xxxxx
GMAIL_REFRESH_TOKEN=xxxxx
````

## **4\. Probar el proyecto**

Por último, para fines prácticos, abrir Postman y hacer la siguiente petición mediante post:

POST:
> http://127.0.0.1:8080/oauth2-callback
