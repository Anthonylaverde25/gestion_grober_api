# Plan de Implementación: Sistema de Gestión de Consorcios y Fundición

**Fecha de Creación:** lunes, 27 de abril de 2026

Este documento detalla la hoja de ruta técnica para el desarrollo del sistema, siguiendo los mandatos de **Arquitectura Limpia** y **Aislamiento Biológico** definidos en `GEMINI.md`.

## 1. Arquitectura de Datos (Fase 0)

Se ha optado por el uso de **UUID v7** para las entidades principales. A diferencia de las versiones anteriores, el v7 es **cronológicamente ordenable** (time-ordered), lo que ofrece ventajas críticas:

- **Rendimiento de Indexación:** Al incluir un timestamp en los bits de mayor peso, la base de datos inserta los registros de forma secuencial, evitando la fragmentación de índices B-Tree típica de los UUID aleatorios.
- **Seguridad y Privacidad:** Mantiene la opacidad frente a terceros (no es predecible), pero permite ordenamiento nativo por fecha de creación sin depender exclusivamente de una columna `created_at`.
- **Desacoplamiento Total:** La identidad de la entidad se genera en la capa de Dominio, garantizando el aislamiento biológico del núcleo respecto al motor de persistencia.

### 1.1. Esquema de Tablas Refinado

Todas las entidades principales incluirán **SoftDeletes** (`deleted_at`). La autenticación se manejará con el modelo `User` estándar de Laravel, delegando la información extendida a una futura tabla `profiles`.

1. **users** (Infraestructura de Laravel)
   - `id`: BigInt (PK)
   - Campos estándar (email, password, etc.)

2. **roles**
   - `id`: BigInt (PK)
   - `name`: String, `slug`: String, `description`: String

3. **consortia**
   - `id`: UUID (PK), `name`: String, `is_active`: Boolean
   - `deleted_at`: Timestamp

4. **companies**
   - `id`: UUID (PK)
   - `consortium_id`: UUID (FK)
   - `manager_id`: BigInt (FK -> users.id) - **Responsable directo de planta**
   - `name`: String, `is_active`: Boolean
   - `deleted_at`: Timestamp

5. **glass_types**
   - `id`: BigInt (PK), `name`: String

6. **furnaces**
   - `id`: UUID (PK)
   - `company_id`: UUID (FK)
   - `glass_type_id`: BigInt (FK)
   - `name`: String, `max_capacity_tons`: Decimal, `current_status`: String
   - `deleted_at`: Timestamp

6. **company_user** (Relación M:N con Roles)
   - `user_id`: BigInt (FK)
   - `company_id`: UUID (FK, Nullable para acceso global)
   - `role_id`: BigInt (FK)

---

## 2. Etapas de Desarrollo

### Etapa 0: Cimentación (Persistencia)
- Generación de migraciones según el esquema refinado.
- Configuración de modelos Eloquent en `app/Models` como capas de datos de infraestructura.
- Configuración de SoftDeletes para integridad histórica.
- Creación y ejecución de Seeders:
    - **Roles:** SuperAdmin, Admin, Owner, CompanyManager, Operator, Viewer.
    - **Consorcio:** "Cattorini".
    - **Empresas:** "Cristalerías Cattorini Hnos", "Rigolleau".
    - **Usuarios Iniciales:**
        - **Grober Laverde:** Rol Admin (Global).
        - **Sergio:** Rol Owner (Consorcio).
    - **Fakers:** Generación de 10 usuarios operativos y 5 hornos por empresa vía Factories.

### Etapa 1: Núcleo del Negocio (Core Domain)
- Implementación de **Entidades Ricas** en `app/Core/Domain/Entities`.
- Definición de **Repository Interfaces** en `app/Core/Domain/Repositories`.
- Creación de **Value Objects** para validaciones intrínsecas.

### Etapa 2: Lógica de Aplicación (Use Cases)
- Desarrollo de Casos de Uso (ej. `RegisterProduction`, `AssignUserToCompany`).
- Implementación de **DTOs** para el paso de datos entre controladores y el Core.

### Etapa 3: Infraestructura y Aislamiento
- Implementación de Repositorios concretos usando Eloquent.
- Creación de **Mappers** (Eloquent <-> Domain Entity).
- Implementación del **Tenant Manager** para filtrar datos automáticamente según el contexto del usuario.

### Etapa 4: Entrega (API Presentation)
- Controladores delgados (Slim Controllers).
- **JsonResources** para la estandarización de respuestas.
- Middleware de autorización basado en el esquema de roles M:N.

---

## 3. Estado de Ejecución
- [x] Etapa 0: Migraciones (Finalizado: lunes, 27 de abril de 2026, 11:45 AM)
- [x] Etapa 0: Modelos Base (Finalizado: lunes, 27 de abril de 2026, 11:45 AM)
- [x] Etapa 0: Seeders e información inicial (Finalizado: lunes, 27 de abril de 2026, 11:45 AM)
- [ ] Etapa 1: Entidades de Dominio
- [ ] Etapa 2: Casos de Uso Iniciales
