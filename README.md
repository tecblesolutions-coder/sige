# SIGE - Sistema de Gestión de Empleados

<p align="center">
  <strong>Un sistema moderno y centralizado para la gestión de recursos humanos.</strong>
</p>

---

## 📖 Sobre el Proyecto

**SIGE** es una aplicación web construida con Laravel y el stack TALL (Tailwind, Alpine.js, Laravel, Livewire) que provee una solución integral para la administración de personal de una organización. Permite llevar un control detallado de los empleados, gestionar su asistencia diaria, y generar reportes, todo a través de una interfaz de usuario interactiva y adaptable.

El sistema está diseñado con una robusta arquitectura de roles y permisos, asegurando que cada usuario tenga acceso únicamente a la información y funcionalidades que le corresponden.

---

## ✨ Características Principales

*   **Gestión de Empleados:** CRUD completo para perfiles de empleados, incluyendo información personal y laboral.
*   **Control de Asistencia:**
    *   **Módulo de Administrador:** Hoja de asistencia diaria para ver el estado de todos los empleados y marcar entradas/salidas en su nombre.
    *   **Módulo de Empleado:** Página de auto-servicio ("Mi Asistencia") para que cada empleado marque su propia asistencia y consulte su historial.
*   **Sistema de Roles y Permisos:** Roles predefinidos (Admin, Empleado, Analista, Auditor) con permisos específicos para cada funcionalidad.
*   **Gestión de Catálogos:** Administración centralizada de datos como Países, Departamentos, Cargos, etc.
*   **Chatbot de Asistencia:** Un asistente virtual con un sistema de "drivers" que permite cambiar entre:
    *   Un chatbot local basado en reglas (gratuito).
    *   Un chatbot con IA conectado a la API de Google Gemini.
*   **Diseño Adaptable y Modo Oscuro:** Interfaz de usuario moderna construida con Tailwind CSS y preparada para una excelente visualización en cualquier dispositivo.

---

## 💻 Stack Tecnológico

*   **Backend:** Laravel 10
*   **Frontend:** Livewire & Alpine.js
*   **Estilos:** Tailwind CSS
*   **Base de Datos:** MySQL (configurable)
*   **Autenticación:** Laravel Jetstream

---

## 🚀 Instalación y Configuración

Sigue estos pasos para poner en marcha el proyecto en tu entorno de desarrollo local.

### 1. Clonar el Repositorio
```bash
git clone <URL_DEL_REPOSITORIO>
cd <NOMBRE_DE_LA_CARPETA>
```

### 2. Instalar Dependencias
Asegúrate de tener Composer y Node.js instalados.
```bash
# Instalar dependencias de PHP
composer install

# Instalar dependencias de JavaScript
npm install
```

### 3. Configuración del Entorno
```bash
# Copia el archivo de ejemplo para el entorno
cp .env.example .env

# Genera la clave de la aplicación
php artisan key:generate
```

### 4. Configurar el Archivo `.env`
Abre el archivo `.env` y configura las siguientes variables, como mínimo:

*   **Base de Datos:**
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=tu_base_de_datos
    DB_USERNAME=tu_usuario
    DB_PASSWORD=tu_contraseña
    ```

*   **Chatbot (Opcional):**
    ```ini
    # Para usar el chatbot de Gemini, añade tu clave
    GEMINI_API_KEY=tu_clave_de_google_gemini_api

    # Selecciona el driver del chatbot ('rules' o 'gemini')
    CHATBOT_DRIVER=rules
    ```

### 5. Compilar los Assets
```bash
npm run build
```

---

## ▶️ Ejecución del Proyecto

### 1. Migraciones y Seeders
Este es el paso más importante para tener una base de datos funcional con datos de prueba.

```bash
# Este comando borrará tu base de datos, la volverá a crear y la llenará con datos de prueba
php artisan migrate:fresh --seed
```

### 2. Levantar el Servidor
```bash
php artisan serve
```
¡Y listo! La aplicación estará corriendo en `http://127.0.0.1:8000`.

---

## 👥 Usuarios de Prueba

Puedes iniciar sesión con los siguientes usuarios creados por defecto.

| Rol          | Usuario      | Contraseña  |
|--------------|--------------|-------------|
| Administrador| `admin`      | `Admin123*` |
| Analista     | `analista`   | `123456789` |
| Auditor      | `auditor`    | `123456789` |
| Empleado     | `empleado`   | `123456789` |

Además, el seeder `DemoDataSeeder` crea **20 usuarios adicionales** con el rol "Empleado" y contraseñas `password` para pruebas de volumen.
