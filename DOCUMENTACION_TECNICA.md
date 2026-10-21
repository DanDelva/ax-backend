# Documentación Técnica Exhaustiva: AX-Backend

Este documento es la guía definitiva y detallada del sistema de gestión escolar "AX-Backend". Está diseñado para servir como manual de referencia para desarrolladores, administradores de sistemas y soporte técnico.

---

## 1. Arquitectura y Stack Tecnológico

El proyecto está construido sobre **Laravel 11** (un framework de PHP moderno) y utiliza el patrón de diseño MVC (Modelo-Vista-Controlador).

### Tecnologías Core:
- **Backend:** PHP 8.x
- **Framework:** Laravel 11.x
- **Base de Datos:** MySQL / MariaDB (comúnmente operado a través de Laragon)
- **ORM:** Eloquent (Sistema de base de datos nativo de Laravel)
- **Motor de Plantillas:** Blade
- **Frontend / UI:** Tailwind CSS (Framework de utilidades CSS)

---

## 2. Estructura de Directorios Críticos

Si necesitas modificar algo en el sistema, estas son las carpetas exactas donde debes buscar:

- `app/Models/`: Contiene los Modelos (Entidades de la base de datos). Aquí se definen las relaciones (Ej. un Estudiante tiene muchas Asistencias).
- `app/Http/Controllers/Web/`: Contiene los Controladores. Aquí vive el "cerebro" de la aplicación. Las funciones de guardar, editar, listar y eliminar están aquí.
- `routes/web.php`: Archivo donde se declaran las URLs. Si quieres que el sistema responda a la URL `/mi-pagina`, debes registrarla aquí.
- `resources/views/`: Todas las pantallas que ve el usuario (archivos `.blade.php`).
  - `resources/views/layouts/app.blade.php`: Es la plantilla "maestra". Aquí está el menú lateral y la barra superior que se repite en todas las páginas.
- `database/migrations/`: El historial de la base de datos. Cada vez que se crea o altera una tabla, se genera un archivo aquí.
- `public/`: Los archivos públicos accesibles desde el navegador (imágenes, CSS compilado, JavaScript).

---

## 3. Diccionario de Datos (Modelos y Relaciones)

El sistema opera con tablas interconectadas. Aquí el detalle de cada entidad:

### `User` (Usuarios)
La tabla central de acceso. Todos los actores inician sesión aquí.
- **Campos clave:** `name`, `email`, `password`, `role` (`admin`, `docente`, `estudiante`), `matricula` (para estudiantes).
- **Relaciones:** Puede tener muchos `Enrollments` (si es estudiante), muchas `Subjects` (si es docente impartiendo clases), y muchas `Attendances`/`Grades`.

### `Specialty` (Especialidades / Carreras)
Agrupa un conjunto de materias. 
- **Campos clave:** `title`, `description`.
- **Relaciones:** Tiene muchos `Subjects` y muchos alumnos inscritos a través de `Enrollment`.

### `Subject` (Materias)
Las clases individuales que se imparten.
- **Campos clave:** `title`, `specialty_id` (a qué carrera pertenece), `teacher_id` (qué docente la da).
- **Regla:** Una materia siempre debe tener un docente asignado para que aparezca en el panel de ese docente.

### `Enrollment` (Inscripciones)
Tabla "pivote" que formaliza que un alumno está estudiando una carrera.
- **Campos clave:** `user_id` (alumno), `specialty_id` (carrera), `status`, `inscription_fee` (costo de inscripción), `monthly_fee` (mensualidad).

### `Attendance` (Asistencias)
- **Campos clave:** `user_id`, `subject_id`, `date` (fecha de la clase), `status` (`presente`, `falta`, `retardo`, `justificado`).
- **Comportamiento:** Si un maestro pasa lista rápida, el sistema crea o actualiza en bloque múltiples registros en esta tabla.

### `Grade` (Calificaciones)
- **Campos clave:** `user_id`, `subject_id`, `type` (`teorica`, `practica`), `score` (numérico decimal).
- **Regla:** Las calificaciones se filtran estrictamente para que los alumnos solo vean las suyas, y los maestros solo califiquen sus propias materias.

### `Payment`, `Schedule`, `Excuse`
Gestionan la administración secundaria (Pagos, Horarios de clase y Justificantes médicos/personales).

---

## 4. Reglas de Negocio, Filtros y Seguridad

> [!IMPORTANT]
> El sistema es extremadamente estricto con la privacidad de los datos mediante el objeto `Auth::user()`.

### Aislamiento por Roles
1. **Administrador:** Puede crear, leer, actualizar y borrar cualquier registro.
2. **Docente:**
   - **Módulo de Asistencias y Calificaciones:** En los controladores correspondientes, el código verifica `$user->isDocente()` e inyecta una condición `where('teacher_id', $user->id)` a nivel de base de datos. Esto hace imposible que un maestro altere los datos de otro maestro, incluso si manipula la URL.
   - **Desplegables Dinámicos:** Cuando un docente va a calificar o pasar lista, el sistema no carga a todos los estudiantes de la escuela. Primero busca qué materias da el maestro -> luego busca a qué especialidades pertenecen esas materias -> finalmente consulta la tabla `Enrollments` para obtener únicamente a los alumnos de esas especialidades.
3. **Estudiante:** Su vista está bloqueada mediante middlewares. Si intentan acceder a una URL de edición (Ej: `/attendances/create`), el sistema arroja un Error 403 (Acceso Denegado).

### Pase de Lista Rápido (Bulk Update)
Diseñado para la eficiencia del docente. El controlador `AttendanceController@quickStore` recibe un arreglo gigante de alumnos. Utiliza `updateOrCreate` de Laravel para asegurar que si un docente se equivoca y vuelve a pasar lista el mismo día para la misma materia, el sistema **no duplique registros**, sino que simplemente actualice el estatus (ej. cambiar una Falta por un Retardo).

---

## 5. Guía de Configuración e Instalación desde Cero

Si el servidor se destruye o necesitas instalar el sistema en otra computadora:

1. **Clonar/Copiar el proyecto** en la carpeta `www` de Laragon (ej: `c:\laragon\www\ax-backend`).
2. **Instalar dependencias de PHP:** Abrir la terminal en esa carpeta y ejecutar `composer install`.
3. **Instalar dependencias de Frontend:** Ejecutar `npm install`.
4. **Configurar el entorno:** Copiar el archivo `.env.example` y renombrarlo a `.env`.
5. **Base de Datos:** En el archivo `.env`, configurar `DB_DATABASE=nombre_de_tu_bd` y crear esa base de datos vacía en el MySQL de Laragon.
6. **Generar llave:** Ejecutar `php artisan key:generate`.
7. **Crear las tablas:** Ejecutar `php artisan migrate`.
8. **Compilar el diseño:** Ejecutar `npm run build` para generar el CSS final.

---

## 6. Resolución de Problemas y Diagnóstico (Troubleshooting Avanzado)

### 🔴 Error Crítico: "SQLSTATE[HY000] [1045] Access denied for user"
- **Causa:** El sistema no puede entrar a la base de datos MySQL.
- **Solución:** Revisa tu archivo `.env`. Confirma que `DB_PORT=3306`, `DB_USERNAME=root` y que `DB_PASSWORD` esté vacío (si usas Laragon por defecto) o tenga tu contraseña correcta.

### 🔴 Error: Al intentar guardar dice "Base table or view not found"
- **Causa:** Te falta una tabla en la base de datos. Alguien creó código nuevo pero la base de datos está desactualizada.
- **Solución:** Ejecuta `php artisan migrate` en la terminal.

### 🔴 Comportamiento Extraño: Un alumno pagó pero no aparece en su panel
- **Diagnóstico:** Revisa la tabla `payments`. Verifica que el `user_id` asociado al pago sea realmente el ID de ese alumno. 
- **Solución rápida:** Como administrador, puedes editar el pago desde el panel web y reasignar el estudiante correcto.

### 🔴 Error 419: "Page Expired" al enviar un formulario
- **Causa:** El token de seguridad de Laravel expiró por inactividad o falta en el código.
- **Solución:** Si dejaste la página abierta mucho tiempo, recárgala. Si eres programador y acabas de crear un formulario nuevo, asegúrate de haber puesto `@csrf` justo debajo de la etiqueta `<form>`.

### 🔴 Lentitud en el Sistema o Cambios que no se reflejan
- **Causa:** Laravel almacena caché de configuraciones y vistas para ser más rápido, pero a veces interfiere en el desarrollo.
- **Solución:** Limpia absolutamente todo con los comandos:
  - `php artisan optimize:clear`
  - `php artisan view:clear`
  - `php artisan cache:clear`

### 🔴 Un Administrador olvidó su contraseña
- **Solución manual (Si no hay recuperación por correo):** 
  Abre la base de datos con HeidiSQL o PhpMyAdmin. Ve a la tabla `users`, busca el registro del administrador y cambia su campo `password` por este texto exacto:
  `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`
  Esto reiniciará su contraseña a la palabra **`password`**. Podrá entrar y luego cambiarla desde el sistema.
