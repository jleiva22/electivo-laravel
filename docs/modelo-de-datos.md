# Modelo de datos - Sistema de Postulación a Electivos

El siguiente diagrama muestra las tablas principales y sus relaciones (según los requerimientos del sistema).

```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email
        string password
        timestamps
    }

    ALUMNOS {
        bigint id PK
        bigint user_id FK
        string rut
        string curso
        string nivel  "3°|4°"
        timestamps
    }

    ROLES {
        bigint id PK
        string name
        string guard_name
        timestamps
    }

    MODEL_HAS_ROLES {
        bigint role_id FK
        bigint model_id FK
        string model_type
    }

    AREAS {
        bigint id PK
        string nombre
        string descripcion
        timestamps
    }

    SECTORS {
        bigint id PK
        bigint area_id FK
        string nombre
        string descripcion
        timestamps
    }

    ELECTIVOS {
        bigint id PK
        bigint area_id FK
        bigint sector_id FK
        string nombre
        text descripcion
        string pdf_url
        enum nivel  "3,4,both"
        boolean activo
        timestamps
    }

    PROCESOS_POSTULACION {
        bigint id PK
        string nombre
        text descripcion
        enum estado  "active,closed"
        int max_total
        int max_por_area
        datetime inicio nullable
        datetime termino nullable
        boolean restringir_a_grupos
        timestamps
    }

    POSTULACION_ALUMNOS {
        bigint id PK
        bigint proceso_id FK
        bigint alumno_id FK
        enum estado  "open,finalizado,locked"
        timestamps
    }

    SELECCIONES {
        bigint id PK
        bigint postulacion_alumno_id FK
        bigint electivo_id FK
        timestamps
    }

    GRUPOS {
        bigint id PK
        string nombre
        text descripcion
        timestamps
    }

    GRUPO_ALUMNO {
        bigint id PK
        bigint grupo_id FK
        bigint alumno_id FK
        timestamps
    }

    USERS ||--o{ ALUMNOS : "tiene"
    ALUMNOS ||--o{ POSTULACION_ALUMNOS : "hace"
    POSTULACION_ALUMNOS ||--o{ SELECCIONES : "contiene"
    ELECTIVOS ||--o{ SELECCIONES : "es"

    AREAS ||--o{ SECTORS : "contiene"
    AREAS ||--o{ ELECTIVOS : "contiene"
    SECTORS ||--o{ ELECTIVOS : "contiene"

    PROCESOS_POSTULACION ||--o{ POSTULACION_ALUMNOS : "incluye"

    GRUPOS ||--o{ GRUPO_ALUMNO : "tiene"
    ALUMNOS ||--o{ GRUPO_ALUMNO : "pertenece"

    ROLES ||--o{ MODEL_HAS_ROLES : "asigna"
    USERS ||--o{ MODEL_HAS_ROLES : "tiene"
```

> **Notas clave:**
> - Se considera normalización 4FN: cada selección de electivo se guarda en una tabla de relación (`SELECCIONES`) para permitir múltiples electivos por postulación.
> - `PROCESOS_POSTULACION` gestiona reglas (máximo total + máximo por área) y controla el estado (activo/cerrado).
> - `ALUMNOS` se vincula a `USERS` para seguir el esquema de autenticación estándar de Laravel.
> - `GRUPOS` y `GRUPO_ALUMNO` permiten asignar procesos a grupos de alumnos cuando se requiere.
> - El modelo de roles sigue el patrón usado por paquetes como Spatie (opcional).
