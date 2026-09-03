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

* **Modelos:** representan los datos y la lógica relacionada con ellos (`Categoria`, `Producto`, `Usuario`, `Carrito`, `Item`, `Compra`).
* **Controladores:** reciben las peticiones HTTP, utilizan los modelos y preparan la información necesaria para las vistas o para la respuesta JSON.
* **Vistas:** utilizan Blade para generar el HTML que se muestra al usuario.
* **Rutas:** definen las URLs disponibles y determinan qué controlador y método debe procesar cada petición.

El proyecto expone dos flujos distintos:

* **Flujo web** (`routes/web.php`): gestión de productos mediante vistas Blade.
* **Flujo API** (`routes/api.php`): gestión de categorías, productos, usuarios, carritos, items y compras mediante respuestas JSON, pensado para el feature de carrito de compras.

## Flujo general (vistas)

1. El usuario realiza una petición a una URL de la aplicación.
2. Laravel recibe la petición y busca una ruta que coincida.
3. La ruta determina qué método del `ProductoController` debe ejecutarse.
4. El controlador utiliza el modelo `Producto` para consultar o modificar los datos.
5. El controlador prepara la información necesaria.
6. La vista Blade recibe los datos y genera el HTML.
7. Tailwind CSS proporciona los estilos utilizados por las vistas.

Esto respeta el patrón MVC porque la vista se encarga de la presentación, mientras que el controlador coordina el flujo y el modelo se encarga de los datos.

## Flujo general (API)

1. El cliente (por ejemplo, Postman o un frontend) realiza una petición a una URL bajo `/api`.
2. Laravel recibe la petición y busca una ruta que coincida dentro de `routes/api.php`.
3. La ruta determina qué método del controlador correspondiente debe ejecutarse (`CategoriaController`, `ProductoController`, `UsuarioController`, `CarritoController`, `ItemController` o `CompraController`).
4. El controlador utiliza el modelo correspondiente para consultar o modificar los datos.
5. El controlador devuelve la respuesta directamente en formato JSON, sin pasar por una vista.

Este flujo es el que se utiliza para agregar y quitar productos de un carrito, y para realizar la compra.

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

# Rutas de la API

Las rutas de la API se encuentran en `routes/api.php` y están disponibles bajo el prefijo `/api`.

## Seguridad de rutas y JWT

La API no aplica un middleware global a todas las rutas, porque eso impediría el flujo normal de autenticación: el registro y el login deben ser públicos para permitir que el cliente obtenga un token JWT antes de acceder a recursos protegidos.

### Rutas públicas

Estas rutas son accesibles sin autenticación:

| Método | URL | Motivo |
| ------ | --- | ------ |
| POST | `/api/auth/register` | Permite crear un usuario y obtener acceso a la API. |
| POST | `/api/auth/login` | Emite el JWT que usará el cliente en las siguientes peticiones. |

### Rutas protegidas por JWT

Estas rutas requieren un token JWT válido:

| Método | URL | Motivo |
| ------ | --- | ------ |
| POST | `/api/auth/logout` | Cierra la sesión del usuario autenticado. |
| POST | `/api/auth/refresh` | Renueva el token actual. |
| GET | `/api/auth/me` | Devuelve el perfil del usuario autenticado. |

El middleware personalizado se registra con el alias `autenticar.jwt`. Se usa un alias propio para evitar conflictos con aliases registrados por paquetes de autenticación.

### Protección frente a CSRF, XSS y SQL Injection

La API utiliza autenticación stateless mediante el encabezado `Authorization: Bearer <token>`, no autenticación basada en cookies de sesión. Por eso no se añade el middleware CSRF de formularios de Laravel a `routes/api.php`: el token Bearer no se envía automáticamente en una petición cross-site.

El middleware global `SeguridadApi`, añadido al grupo `api`, rechaza las solicitudes de modificación que incluyan una cookie de sesión pero no un token Bearer. Esto evita reutilizar accidentalmente la sesión web como mecanismo de autenticación de la API y reduce el riesgo de CSRF.

Para reducir XSS, todas las respuestas de la API se sirven con cabeceras `Content-Security-Policy`, `X-Content-Type-Options` y `X-Frame-Options`. Laravel serializa las respuestas mediante JSON, y los datos recibidos se validan antes de almacenarse; los clientes deben tratar los valores JSON como texto y no insertarlos como HTML sin escapar.

Para evitar SQL Injection, las consultas se realizan mediante Eloquent y Query Builder, que usan PDO parameter binding. Las escrituras utilizan `$request->validate()` y `$request->validated()` o los campos validados explícitamente; no se interpolan valores recibidos del cliente en SQL ni se usa `$request->all()` para asignación masiva.

### Rutas protegidas por JWT + propietario del recurso

Estas rutas tienen dos validaciones:

- `autenticar.jwt`: valida que el token sea válido y no haya expirado.
- `jwt.cart.owner`: verifica que el usuario autenticado es el propietario del carrito o compra que intenta consultar/modificar.

| Método | URL | Motivo |
| ------ | --- | ------ |
| GET/POST/PUT/DELETE | `/api/items` | Un usuario solo puede manipular sus propios items del carrito. |
| GET/POST | `/api/carritos` | El carrito pertenece al usuario autenticado. |
| GET/PUT/DELETE | `/api/compras` | Las compras y su historial deben estar asociados al usuario autenticado. |
| POST | `/api/compras/{usuario}/checkout` | El checkout solo puede ejecutarse para el usuario actual y con su carrito. |

Esto evita que un cliente acceda, modifique o elimine recursos de otra persona aunque conozca la URL.

## Categorías

| Método | URL                       | Acción                |
| ------ | ------------------------- | ---------------------- |
| GET    | `/api/categorias`         | Listar categorías       |
| POST   | `/api/categorias`         | Crear una categoría     |
| GET    | `/api/categorias/{categoria}` | Ver una categoría   |
| PUT    | `/api/categorias/{categoria}` | Actualizar una categoría |
| DELETE | `/api/categorias/{categoria}` | Eliminar una categoría |

## Productos

| Método | URL                          | Acción                |
| ------ | ---------------------------- | ---------------------- |
| GET    | `/api/productos`             | Listar productos        |
| POST   | `/api/productos`             | Crear un producto       |
| GET    | `/api/productos/{producto}`  | Ver un producto         |
| PUT    | `/api/productos/{producto}`  | Actualizar un producto  |
| DELETE | `/api/productos/{producto}`  | Eliminar un producto    |

## Usuarios

| Método | URL                        | Acción               |
| ------ | -------------------------- | ---------------------- |
| GET    | `/api/usuarios`            | Listar usuarios         |
| POST   | `/api/usuarios`            | Crear un usuario        |
| GET    | `/api/usuarios/{usuario}`  | Ver un usuario          |
| PUT    | `/api/usuarios/{usuario}`  | Actualizar un usuario   |
| DELETE | `/api/usuarios/{usuario}`  | Eliminar un usuario     |

Al crear un usuario, se genera automáticamente su carrito de compras.

## Carritos

| Método | URL                              | Acción                 |
| ------ | --------------------------------- | ----------------------- |
| GET    | `/api/carritos`                   | Listar carritos           |
| GET    | `/api/carritos/{carrito}`         | Ver un carrito             |
| POST   | `/api/carritos/{carrito}/empty`   | Vaciar un carrito           |

## Items

Los items representan los productos agregados a un carrito.

| Método | URL                    | Acción                    |
| ------ | ----------------------- | -------------------------- |
| GET    | `/api/items`             | Listar items                 |
| POST   | `/api/items`             | Agregar un producto a un carrito |
| GET    | `/api/items/{item}`      | Ver un item                  |
| PUT    | `/api/items/{item}`      | Actualizar la cantidad de un item |
| DELETE | `/api/items/{item}`      | Eliminar un item de un carrito |

## Compras

| Método | URL                                 | Acción                        |
| ------ | ------------------------------------ | ------------------------------ |
| GET    | `/api/compras`                       | Listar compras                    |
| GET    | `/api/compras/{usuario}`             | Ver las compras de un usuario     |
| POST   | `/api/compras/{usuario}/checkout`    | Realizar el checkout del carrito de un usuario |
| PUT    | `/api/compras/{compra}`              | Actualizar una compra              |
| DELETE | `/api/compras/{compra}`              | Eliminar una compra                |

El checkout genera una compra por cada item del carrito del usuario, descuenta el stock de los productos correspondientes y elimina esos items del carrito.

# Estructura de carpetas

La estructura principal del proyecto sigue la estructura estándar de Laravel:

```text
Alkemy/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── CarritoController.php
│   │       ├── CategoriaController.php
│   │       ├── CompraController.php
│   │       ├── Controller.php
│   │       ├── ItemController.php
│   │       ├── ProductoController.php
│   │       └── UsuarioController.php
│   └── Models/
│       ├── Carrito.php
│       ├── Categoria.php
│       ├── Compra.php
│       ├── Item.php
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
│   ├── api.php
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
* `routes/`: contiene la definición de las rutas de la aplicación. `web.php` define las rutas que devuelven vistas Blade, y `api.php` define las rutas que devuelven respuestas JSON.
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

Al acceder a `/productos` desde el navegador, la aplicación muestra el listado de productos junto con la información correspondiente, como su categoría y precio. Desde esta sección también se pueden realizar las operaciones de creación, edición y eliminación de productos.

Al consumir las rutas bajo `/api`, la aplicación devuelve la información en formato JSON. Por ejemplo, al hacer `GET /api/productos` se obtiene el listado de productos junto con su categoría asociada.