# Ejecución: AUTH_API_PLAN - Paso 5 (Controller y Rutas)

**Fecha:** lunes, 27 de abril de 2026, 02:55 PM

## 1. Resumen de Acciones
Se ha implementado el controlador de autenticación y se han registrado las rutas públicas y protegidas en el sistema.

## 2. Archivos Creados / Modificados
- **Creado:** `app/Http/Requests/Auth/LoginRequest.php`: Validación sintáctica de entrada.
- **Creado:** `app/Http/Resources/V1/LoginResource.php`: Formateo de respuesta de sesión.
- **Creado:** `app/Http/Controllers/V1/AuthController.php`: Gestión de login/logout.
- **Modificado:** `routes/api.php`: Registro de endpoints `/v1/auth/login` y `/v1/auth/logout`.

## 3. Decisiones Técnicas
- **Gestión de Excepciones:** El controlador captura excepciones del core para devolver respuestas JSON con código `401`, manteniendo el contrato de la API limpio.
- **Uso de Mappers en Logout:** Se utiliza `UserMapper` para convertir el objeto de autenticación de Laravel al dominio antes de enviarlo al servicio, respetando el aislamiento.
- **Seguridad de Rutas:** El endpoint de `logout` se colocó dentro del middleware `auth:sanctum` para asegurar que solo un usuario con sesión activa pueda cerrarla.
