# 📘 Fija Cuadrera

Aplicación web para la gestión de finanzas personales o familiares. Permite llevar un registro detallado de ingresos, egresos, tarjetas de crédito asociadas y cierres mensuales con historial. Ideal para organizar tu economía de forma clara, visual y ordenada.

## 🌐 Demo en Producción

Accedé a la app en funcionamiento desde el siguiente enlace:

🔗 [https://fijacuadrera.com/login](https://fijacuadrera.com/login)

---

## ⚙️ Tecnologías Utilizadas

- **Backend:** Laravel 10
- **Frontend:** Vue 3 + Composition API
- **Motor SPA:** Inertia.js
- **Estilos:** TailwindCSS
- **Base de Datos:** MySQL / MariaDB
- **Autenticación:** Token manual personalizado
- **Persistencia:** Axios + LocalStorage

---

## 🧠 ¿Qué permite hacer esta app?

- Registrar usuarios con email y contraseña.
- Iniciar sesión y mantener sesión con token persistente.
- Agregar ingresos con descripción, monto y fecha.
- Agregar egresos únicos, mensuales o en cuotas.
- Asociar egresos a tarjetas de crédito.
- Crear tarjetas personalizadas.
- Visualizar todos los ingresos y egresos agrupados por mes.
- Ver diferencia mensual (ingresos - egresos).
- Cerrar meses para consolidar el estado financiero.
- Ver historial de cierres mensuales anteriores.

---

## 🧱 Estructura de Base de Datos

- **usuarios**  
  Contiene los datos de acceso (nombre, email, password).

- **ingresos**  
  Referencia a `usuario_id`. Cada ingreso tiene monto, descripción y fecha.

- **egresos**  
  Referencia a `usuario_id` y `tarjeta_id` (opcional). Maneja cuotas, tipos de egresos, fechas y montos por cuota.

- **tarjetas**  
  Relacionadas a `usuario_id`. Pueden asociarse a uno o más egresos.

- **cierres_mensuales**  
  Guarda el resumen financiero por mes y año. Incluye totales de ingresos, egresos, diferencia y cantidades.

- **tokens**  
  Tabla para gestionar el inicio de sesión con tokens de autenticación.

---

## 🔌 Requisitos

- PHP >= 8.1
- Composer
- Node.js >= 16
- MySQL o MariaDB
- NPM o Yarn

---

## 🚀 Instalación Local

1. Instalar dependencias de Laravel y Vue:

```bash
composer install
npm install
```

2. Crear el archivo de entorno:

```bash
cp .env.example .env
php artisan key:generate
```

3. Configurar la base de datos en `.env`:

```ini
DB_DATABASE=fijacuadrera
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

4. Migrar las tablas:

```bash
php artisan migrate
```

5. (Opcional) Poblar la base con datos:

```bash
php artisan db:seed
```

6. Iniciar los servidores:

```bash
php artisan serve
npm run dev
```

---

## 🛠️ Compilar para Producción

Para generar la build optimizada del frontend:

```bash
npm run build
```

Esto creará los archivos en `public/build`.

---

## 🧾 Rutas principales

- `/login` → Inicio de sesión
- `/registro` → Crear cuenta nueva
- `/home` → Vista principal con movimientos
- `/cerrar-mes` → Vista para cerrar un mes
- `/perfil` → Datos del usuario autenticado

---

## 🔐 Autenticación

La app implementa un sistema de autenticación por token personalizado. Los tokens se guardan en la tabla `tokens` y se pasan en headers (`Authorization: Bearer <token>`) en todas las llamadas protegidas.

El middleware `auth.token` se encarga de verificar que el token exista y esté vinculado a un usuario válido.

---

## 📊 Estructura general

La app funciona como una SPA (Single Page Application) con Inertia.js.

- **Frontend:**  
  Las vistas están en `resources/js/Pages` y los componentes en `resources/js/Components`.

- **Backend:**  
  Los controladores están en `app/Http/Controllers`.  
  Los modelos están en `app/Models`.  
  Las rutas están definidas en `routes/web.php`.

---

## 🧩 Lógica de Cierres Mensuales

Cuando un usuario decide cerrar un mes:
- Se calcula la suma total de ingresos y egresos activos de ese mes.
- Se guarda un resumen en `cierres_mensuales`.
- Los egresos de ese mes pasan a estado `finalizado`.
- El mes ya no aparece más en el home, solo en el historial.

---

## 🧬 Relación entre tablas (resumen)

- `usuarios` tiene muchos → `ingresos`, `egresos`, `tarjetas`, `tokens`
- `egresos` pertenece a → `usuario`, opcionalmente a `tarjeta`
- `tarjetas` tiene muchos → `egresos`
- `cierres_mensuales` pertenece a → `usuario`

---

## 📫 Contacto

Proyecto creado como solución personalizada para organización de finanzas personales.

Acceso online disponible en:

🔗 [https://fijacuadrera.com/login](https://fijacuadrera.com/login)
