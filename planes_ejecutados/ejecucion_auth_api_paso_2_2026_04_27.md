# Ejecución: AUTH_API_PLAN - Paso 2 (DTOs)

**Fecha:** lunes, 27 de abril de 2026, 02:25 PM

## 1. Resumen de Acciones
Se han implementado los objetos de transferencia de datos (DTOs) para el proceso de autenticación.

## 2. Archivos Creados
- `app/Core/Application/DTOs/Auth/LoginInputDTO.php`: Transporta credenciales y nombre de dispositivo desde el controlador.
- `app/Core/Application/DTOs/Auth/AuthOutputDTO.php`: Transporta el resultado exitoso de la autenticación (Entidad + Token).

## 3. Decisiones Técnicas
- **Readonly:** Se utilizan clases `readonly` (PHP 8.2+) para garantizar la inmutabilidad de los datos durante su transporte entre capas.
- **Factory Method:** Se incluyó un método estático `fromRequest` en el Input DTO para desacoplar la estructura del Request de Laravel de la lógica de aplicación.
- **Desacoplamiento:** El Output DTO utiliza la Entidad de Dominio `User` en lugar del modelo Eloquent, respetando el aislamiento del núcleo.
