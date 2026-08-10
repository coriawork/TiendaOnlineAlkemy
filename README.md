# Requisitos

* PHP 8.2 o superior
* Composer
* Node.js y npm
* MySQL

# Instalación

## Instalar dependencias de PHP

Abrir una terminal dentro del proyecto y ejecutar:

```bash
composer install
```

## Instalar dependencias de JavaScript

Ejecutar:

```bash
npm install
```

## Configurar la base de datos

Crear una base de datos MySQL y configurar las credenciales correspondientes en el archivo `.env`.

## Ejecutar el proyecto

Para iniciar el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

Además, el proyecto utiliza **Tailwind CSS** para los estilos, por lo que es necesario ejecutar Vite para compilar los recursos frontend:

```bash
npm run dev
```

Mientras se desarrolla, se recomienda mantener `npm run dev` ejecutándose para que Tailwind detecte y compile los cambios realizados en las vistas.

# Cómo funciona el patrón implementado

El proyecto utiliza **Laravel**, siguiendo una arquitectura basada en el patrón MVC (Model-View-Controller).

* **Modelos:** representan los datos y la lógica relacionada con ellos.
* **Controladores:** reciben las peticiones HTTP, utilizan los modelos y preparan la información necesaria para las vistas.
* **Vistas:** utilizan Blade para generar el HTML que se muestra al usuario.
* **Rutas:** definen las URLs disponibles y determinan qué controlador y método debe procesar cada petición.

## Flujo general

1. El usuario realiza una petición a una URL de la aplicación.
2. Laravel recibe la petición y busca una ruta que coincida.
3. La ruta determina qué método del `ProductoController` debe ejecutarse.
4. El controlador utiliza el modelo `Producto` para consultar o modificar los datos.
5. El controlador prepara la información necesaria.
6. La vista Blade recibe los datos y genera el HTML.
7. Tailwind CSS proporciona los estilos utilizados por las vistas.

Esto respeta el patrón MVC porque la vista se encarga de la presentación, mientras que el controlador coordina el flujo y el modelo se encarga de los datos.

# Rutas de productos

Las operaciones relacionadas con productos se encuentran agrupadas bajo el prefijo `/productos`.

```php
Route::prefix('productos')->group(function () {
    Route::get('/', [ProductoController::class, 'index'])
        ->name('productos');

    Route::get('/crear', [ProductoController::class, 'create'])
        ->name('productos.crear');

    Route::get('/{producto}/editar', [ProductoController::class, 'edit'])
        ->name('productos.editar');

    Route::post('/', [ProductoController::class, 'store'])
        ->name('productos');

    Route::delete('/{producto}', [ProductoController::class, 'destroy'])
        ->name('productos.eliminar');

    Route::put('/{producto}', [ProductoController::class, 'update'])
        ->name('productos.actualizar');
});
```

Las rutas disponibles son:

| Método | URL                            | Acción                         |
| ------ | ------------------------------ | ------------------------------ |
| GET    | `/productos`                   | Listar productos               |
| GET    | `/productos/crear`             | Mostrar formulario de creación |
| POST   | `/productos`                   | Crear un producto              |
| GET    | `/productos/{producto}/editar` | Mostrar formulario de edición  |
| PUT    | `/productos/{producto}`        | Actualizar un producto         |
| DELETE | `/productos/{producto}`        | Eliminar un producto           |

# Estructura de carpetas

La estructura principal del proyecto sigue la estructura estándar de Laravel:

```text
Alkemy/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── ProductoController.php
│   │       └── UsuarioController.php
│   └── Models/
│       ├── Carrito.php
│       ├── Categoria.php
│       ├── Producto.php
│       └── Usuario.php
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── index.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       └── productos/
├── routes/
│   └── web.php
├── storage/
├── vendor/
├── .env
├── artisan
├── composer.json
├── package.json
└── vite.config.js
```

Cada carpeta cumple el siguiente rol:

* `app/`: contiene la lógica principal de la aplicación, incluyendo modelos y controladores.
* `app/Http/Controllers/`: contiene los controladores que procesan las peticiones.
* `app/Models/`: contiene los modelos de Eloquent y la representación de los datos.
* `database/`: contiene las migraciones y seeders de la base de datos.
* `public/`: punto de entrada público de la aplicación.
* `resources/views/`: contiene las vistas Blade de la aplicación.
* `resources/css/`: contiene los estilos CSS y la configuración de Tailwind.
* `resources/js/`: contiene los recursos JavaScript.
* `routes/`: contiene la definición de las rutas de la aplicación.
* `storage/`: contiene archivos generados por Laravel, logs y otros recursos.
* `vendor/`: contiene las dependencias instaladas mediante Composer.
* `.env`: contiene la configuración específica del entorno, como las credenciales de la base de datos.
* `artisan`: herramienta de línea de comandos de Laravel.

# Tailwind CSS

El proyecto utiliza **Tailwind CSS** para los estilos de la interfaz.

Los estilos se encuentran principalmente en:

```text
resources/css/app.css
```

Para que Tailwind compile los estilos y detecte los cambios realizados en las vistas Blade, es necesario ejecutar:

```bash
npm run dev
```

Por lo tanto, durante el desarrollo normalmente se deben tener dos procesos ejecutándose:

```bash
php artisan serve
```

y, en otra terminal:

```bash
npm run dev
```

# Ejemplo de salida

Al acceder a `/productos`, la aplicación muestra el listado de productos junto con la información correspondiente, como su categoría y precio.

Desde esta sección también se pueden realizar las operaciones de creación, edición y eliminación de productos mediante las rutas correspondientes.
