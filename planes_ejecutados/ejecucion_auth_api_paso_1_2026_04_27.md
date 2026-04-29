# Ejecución: AUTH_API_PLAN - Paso 1 (Interfaces de Dominio)

**Fecha:** lunes, 27 de abril de 2026, 02:15 PM

## 1. Resumen de Acciones
Se han definido los contratos de dominio necesarios para la autenticación y se ha creado la entidad de usuario en el Core.

## 2. Archivos Creados
- `app/Core/Domain/Entities/User.php`: Entidad de dominio rica para el usuario.
- `app/Core/Domain/Repositories/AuthRepositoryInterface.php`: Contrato para la validación de credenciales.
- `app/Core/Domain/Services/TokenServiceInterface.php`: Contrato para la gestión de tokens.

## 3. Decisiones Técnicas
- Se creó la entidad `User` en el dominio para desacoplar la identidad del usuario del modelo Eloquent de infraestructura.
- Se definió `TokenServiceInterface` como un servicio de dominio para permitir que la lógica de negocio solicite la generación de tokens sin conocer la implementación (Sanctum/JWT).
