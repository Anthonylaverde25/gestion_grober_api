# Ejecución: Etapa 2 - Lógica de Aplicación (Hornos)

**Fecha:** lunes, 27 de abril de 2026, 12:50 PM

## 1. Resumen de Acciones
Se ha implementado la capa de aplicación para la entidad `Furnace`, estableciendo el patrón **Action Hub (Service)** para desacoplar los controladores de la lógica de orquestación.

## 2. Archivos Creados
- `app/Core/Application/DTOs/Furnace/CreateFurnaceDTO.php`: Objeto de transferencia de datos para creación.
- `app/Core/Application/UseCases/Furnace/CreateFurnace.php`: Caso de uso para la creación de hornos con generación de UUID v7 en el dominio.
- `app/Core/Application/UseCases/Furnace/ListFurnacesByCompany.php`: Caso de uso para filtrado de hornos por empresa.
- `app/Core/Application/Services/FurnaceService.php`: Punto de entrada único (Service Hub) para las operaciones de hornos.

## 3. Decisiones Técnicas
- Se utiliza `Illuminate\Support\Str::uuid()` dentro del Caso de Uso para cumplir con el mandato de que el Dominio sea el dueño de la identidad.
- El `FurnaceService` inyecta los Casos de Uso individuales, permitiendo que el controlador permanezca "Slim" (delgado).
