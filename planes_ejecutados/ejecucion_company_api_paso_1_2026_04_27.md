# Ejecución: COMPANY_API_PLAN - Paso 1 (Casos de Uso)

**Fecha:** lunes, 27 de abril de 2026, 01:10 PM

## 1. Resumen de Acciones
Se ha implementado la lógica de negocio para la consulta de empresas en la capa de Aplicación (Core).

## 2. Archivos Creados / Modificados
- **Modificado:** `app/Core/Domain/Repositories/CompanyRepositoryInterface.php` (Añadido método `all()`).
- **Modificado:** `app/Core/Infrastructure/Persistence/Eloquent/EloquentCompanyRepository.php` (Implementación de `all()`).
- **Creado:** `app/Core/Application/UseCases/Company/ListCompanies.php` (Caso de uso para listado global).
- **Creado:** `app/Core/Application/UseCases/Company/GetCompanyById.php` (Caso de uso para detalle por UUID).

## 3. Decisiones Técnicas
- Se amplió el repositorio de infraestructura para permitir el listado global, necesario para los roles de Administración.
- El caso de uso `GetCompanyById` lanza una `InvalidArgumentException` si la empresa no existe, delegando la gestión del error a las capas superiores o al Exception Handler.
