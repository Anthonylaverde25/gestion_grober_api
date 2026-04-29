# Plan de Implementación Detallado: Autenticación API (Sanctum)

Este documento define la estructura de clases y el flujo de responsabilidades para el sistema de acceso, garantizando que el núcleo del negocio sea agnóstico a la librería de tokens (Sanctum).

## 1. Estructura de Capas

### 1.1. Capa de Aplicación (Application Layer)
Ubicación: `app/Core/Application/...`

- **DTOs (`DTOs/Auth/`):**
    - `LoginInputDTO`: (`email`, `password`, `device_name`).
    - `AuthOutputDTO`: Objeto que agrupa la `UserEntity` y el `plainTextToken`.
- **Casos de Uso (`UseCases/Auth/`):**
    - `AuthenticateUser`: Valida credenciales contra el repositorio y verifica que el usuario esté activo.
    - `RevokeAccess`: Se encarga de la lógica de cierre de sesión.
- **Servicio (`Services/AuthService.php`):**
    - Actúa como fachada para el controlador. Orquesta la validación y la generación del token.

### 1.2. Capa de Dominio (Domain Layer)
Ubicación: `app/Core/Domain/...`

- **Interfaces (`Repositories/`):**
    - `AuthRepositoryInterface`: Define el método `validateCredentials(Email $email, string $password): ?UserEntity`.
    - `TokenServiceInterface`: Define el contrato para generar/revocar tokens (ej. `generate(UserEntity $user, string $name): string`).

### 1.3. Capa de Infraestructura (Infrastructure Layer)
Ubicación: `app/Core/Infrastructure/...`

- **Implementaciones (`Persistence/Eloquent/`):**
    - `EloquentAuthRepository`: Implementa la búsqueda y validación usando `Auth::attempt` o manual.
- **Servicios Externos (`Auth/`):**
    - `SanctumTokenService`: Implementa `TokenServiceInterface` usando los métodos de Sanctum (`createToken`).

### 1.4. Capa Http (Presentation Layer)
Ubicación: `app/Http/...`

- **Controlador (`Controllers/V1/AuthController.php`):** Solo dos métodos: `login` y `logout`.
- **Validación (`Requests/Auth/LoginRequest.php`):** Reglas de Laravel para el input.
- **Transformación (`Resources/V1/LoginResource.php`):** Construye el JSON final:
  ```json
  {
    "data": {
      "user": { "name": "...", "email": "...", "role": "..." },
      "access_token": "...",
      "token_type": "Bearer"
    }
  }
  ```

## 2. Flujo de Ejecución (Login)

1. **Controller** recibe `LoginRequest` (validado).
2. **Controller** llama a `AuthService->login(LoginInputDTO)`.
3. **AuthService** llama a `AuthenticateUser` (Caso de Uso).
4. **UseCase** pide al `AuthRepository` validar credenciales.
5. Si es válido, **AuthService** pide al `TokenServiceInterface` generar el token.
6. **AuthService** retorna un `AuthOutputDTO` al **Controller**.
7. **Controller** retorna un `LoginResource`.

---

## 4. Hoja de Ruta de Ejecución
1. [x] Definición de Interfaces en Dominio (`AuthRepositoryInterface`, `TokenServiceInterface`). (Finalizado: lunes, 27 de abril de 2026, 02:15 PM)
2. [x] Implementación de DTOs de Entrada y Salida. (Finalizado: lunes, 27 de abril de 2026, 02:25 PM)
3. [x] Implementación de la Infraestructura (`EloquentAuthRepository` y `SanctumTokenService`). (Finalizado: lunes, 27 de abril de 2026, 02:35 PM)
4. [x] Desarrollo de los Casos de Uso y el `AuthService`. (Finalizado: lunes, 27 de abril de 2026, 02:45 PM)
5. [x] Creación del Controlador y Registro de Rutas. (Finalizado: lunes, 27 de abril de 2026, 02:55 PM)
