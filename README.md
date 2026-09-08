# Alkemy E-commerce API

API REST para un e-commerce con catálogo, usuarios, carritos, items y checkout. El proyecto está construido con Laravel 13, autenticación JWT y respuestas JSON para la API.

## Requisitos

- PHP 8.3 o superior
- Composer
- Node.js y npm
- MySQL para desarrollo
- SQLite habilitado en PHP para las pruebas

## Instalación

Desde la raíz del proyecto:

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan jwt:secret
npm install
```

En Linux o macOS, el segundo comando equivalente es:

```bash
cp .env.example .env
```

Configurar en `.env` la conexión de desarrollo. Ejemplo con MySQL:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=alkemy
DB_USERNAME=root
DB_PASSWORD=
```

Crear las tablas y cargar datos iniciales:

```bash
php artisan migrate
php artisan db:seed
```

El seeder principal ejecuta `DatosPruebaSeeder`, que genera categorías, productos y usuarios de prueba mediante factories. Para ejecutar únicamente esos datos:

```bash
php artisan db:seed --class=DatosPruebaSeeder
```

## Ejecución

Iniciar Laravel:

```bash
php artisan serve
```

Iniciar Vite durante el desarrollo del frontend:

```bash
npm run dev
```

Para compilar recursos frontend para producción:

```bash
npm run build
```

También puede utilizarse el script combinado del proyecto:

```bash
composer run dev
```

La API queda disponible por defecto en `http://localhost:8000/api`.

## Arquitectura

El proyecto sigue una arquitectura MVC de Laravel, con una capa adicional de DTOs y middleware para los flujos de checkout y autenticación.

```text
app/
├── DTO/                    Datos de entrada y resumen del checkout
├── Http/
│   ├── Controllers/        Endpoints web y API
│   ├── Middleware/         JWT, seguridad API y propiedad del carrito
│   └── Requests/           Validación especializada de productos
├── Models/                 Usuario, categoría, producto, carrito, item y compra
└── Rules/                  Reglas de negocio, como PrecioValido

database/
├── factories/              Datos reutilizables para pruebas
├── migrations/             Esquema y cambios de base de datos
└── seeders/                Datos iniciales y datos de prueba

routes/
├── api.php                 API JSON bajo el prefijo /api
└── web.php                 Rutas web y vistas Blade

tests/
├── Feature/                Flujos HTTP completos
└── Unit/                   Reglas y unidades aisladas
```

### Flujo de una petición API

1. Laravel encuentra la ruta en `routes/api.php`.
2. El grupo `api` ejecuta `SeguridadApi`.
3. Las rutas protegidas validan el JWT con `AutenticarJwtApi`.
4. `VerificarPropietarioCarrito` comprueba que el recurso pertenece al usuario autenticado cuando corresponde.
5. El controlador valida la entrada y utiliza Eloquent/DTOs.
6. La respuesta se devuelve en JSON.

## API

Todas las URLs de esta sección comienzan con `/api`.

### Autenticación

| Método | Endpoint | Autenticación | Descripción |
| --- | --- | --- | --- |
| POST | `/auth/register` | Pública | Registra un usuario, crea su carrito y devuelve un JWT. |
| POST | `/auth/login` | Pública | Valida credenciales y devuelve un JWT. |
| GET | `/auth/me` | JWT | Devuelve el usuario autenticado. |
| POST | `/auth/refresh` | JWT | Renueva el token actual. |
| POST | `/auth/logout` | JWT | Invalida el token actual. |

Registro:

```json
{
  "nombre": "Juan Pérez",
  "correo": "juan@example.com",
  "password": "password123"
}
```

Login:

```json
{
  "correo": "juan@example.com",
  "password": "password123"
}
```

Las respuestas de registro/login incluyen `token`, `token_type` y `expires_in`.

### Encabezado JWT

Para las rutas protegidas enviar:

```http
Authorization: Bearer TU_TOKEN_JWT
Accept: application/json
Content-Type: application/json
```

En Postman se puede seleccionar **Authorization > Bearer Token** y utilizar la variable `jwt_token`.

### Categorías

| Método | Endpoint | Descripción |
| --- | --- | --- |
| GET | `/categorias` | Lista categorías. |
| POST | `/categorias` | Crea una categoría. |
| GET | `/categorias/{categoria}` | Consulta una categoría. |
| PUT | `/categorias/{categoria}` | Actualiza una categoría. |
| DELETE | `/categorias/{categoria}` | Elimina una categoría. |

### Productos

| Método | Endpoint | Descripción |
| --- | --- | --- |
| GET | `/productos` | Lista productos paginados. |
| POST | `/productos` | Crea un producto validando categoría, precio y stock. |
| GET | `/productos/{producto}` | Consulta un producto. |
| PUT | `/productos/{producto}` | Actualiza un producto. |
| DELETE | `/productos/{producto}` | Elimina un producto. |

Ejemplo de alta:

```json
{
  "categoria_id": 1,
  "nombre": "Teclado mecánico",
  "descripcion": "Teclado RGB",
  "precio": 25000,
  "stock": 10
}
```

### Usuarios

| Método | Endpoint | Descripción |
| --- | --- | --- |
| GET | `/usuarios` | Lista usuarios. |
| POST | `/usuarios` | Crea un usuario y su carrito. |
| GET | `/usuarios/{usuario}` | Consulta un usuario. |
| PUT | `/usuarios/{usuario}` | Actualiza un usuario. |
| DELETE | `/usuarios/{usuario}` | Elimina un usuario. |

Las contraseñas se almacenan con bcrypt y no se incluyen en las respuestas JSON.

### Carritos e items

Cada usuario tiene un único carrito. Los endpoints de carrito e items requieren JWT y validan la propiedad del carrito.

| Método | Endpoint | Descripción |
| --- | --- | --- |
| GET | `/carritos` | Lista únicamente el carrito del usuario autenticado. |
| GET | `/carritos/{carrito}` | Consulta el carrito propio. |
| POST | `/carritos/{carrito}/empty` | Vacía el carrito propio. |
| GET | `/items` | Lista items del carrito propio. |
| POST | `/items` | Agrega un producto al carrito. |
| GET | `/items/{carrito_id}/{producto_id}` | Consulta un item. |
| PUT | `/items/{carrito_id}/{producto_id}` | Reemplaza la cantidad del item. |
| DELETE | `/items/{carrito_id}/{producto_id}` | Elimina el item. |

Agregar un item:

```json
{
  "carrito_id": 1,
  "producto_id": 2,
  "cantidad": 2
}
```

Actualizar cantidad:

```json
{
  "cantidad": 3
}
```

La tabla `items` utiliza una clave compuesta (`carrito_id`, `producto_id`). Las cantidades deben ser mayores a cero y no pueden superar el stock disponible.

### Compras y checkout

| Método | Endpoint | Descripción |
| --- | --- | --- |
| GET | `/compras` | Lista compras del flujo autorizado. |
| GET | `/compras/{usuario}` | Lista compras del usuario autenticado. |
| POST | `/compras/{usuario}/checkout` | Genera compras a partir del carrito. |
| PUT | `/compras/{compra}` | Actualiza una compra. |
| DELETE | `/compras/{compra}` | Elimina una compra. |

Checkout:

```json
{
  "metodo_pago": "tarjeta",
  "direccion_envio": "Calle 123",
  "impuesto": 300,
  "envio": 500
}
```

El checkout calcula el subtotal, suma impuestos y envío, crea una compra por item, descuenta stock y elimina los items procesados dentro de una transacción.

## Seguridad

### JWT y autorización

- `AutenticarJwtApi` devuelve `401` si falta el token, es inválido o está vencido.
- `VerificarPropietarioCarrito` devuelve `403` cuando el usuario intenta operar sobre el carrito de otra persona.
- Los aliases se registran en `bootstrap/app.php` como `autenticar.jwt` y `jwt.cart.owner`.
- El usuario se obtiene desde el modelo `Usuario`, que implementa `Authenticatable` y `JWTSubject`.

### CSRF

La API es stateless y usa `Authorization: Bearer`, no cookies de sesión. Por eso no aplica el middleware CSRF de formularios Blade a `routes/api.php`. `SeguridadApi`, agregado al grupo `api`, rechaza mutaciones que presenten una cookie de sesión sin token Bearer.

### XSS

Las respuestas de la API son JSON y `SeguridadApi` agrega `Content-Security-Policy`, `X-Content-Type-Options`, `X-Frame-Options` y `Referrer-Policy`. Los consumidores deben tratar los valores recibidos como texto y escapar cualquier dato antes de insertarlo en HTML.

### SQL Injection y entrada de datos

Las consultas usan Eloquent/Query Builder con binding de parámetros. Las entradas se validan con Form Requests o `$request->validate()` y se escriben únicamente campos validados. No se interpolan valores del usuario en SQL ni se utiliza `$request->all()` para asignación masiva.

### Contraseñas

Las contraseñas se procesan con `Hash::make()` y bcrypt. `Usuario::$hidden` evita exponerlas en serializaciones JSON. En testing se usa `BCRYPT_ROUNDS=4` para acelerar la suite.

## Factories y Seeders

Las factories disponibles son:

- `Usuario::factory()`: usuario con contraseña bcrypt y carrito asociado.
- `Categoria::factory()`: categoría con datos Faker.
- `Producto::factory()`: producto relacionado con una categoría.
- `Producto::factory()->sinStock()`: producto con stock cero.
- `Producto::factory()->conStock(10)`: producto con stock controlado.

`DatosPruebaSeeder` combina estas factories para crear categorías, productos relacionados y usuarios con carrito. `DatabaseSeeder` lo utiliza como seeder principal del entorno de datos de prueba.

## Tests y calidad del código

El proyecto usa PHPUnit 13 como motor y Pest 5 como sintaxis. `phpunit.xml` configura SQLite en memoria, y `RefreshDatabase` aísla cada Feature test. Los tests se dividen en:

- **Unit:** reglas, DTOs, casts y controladores aislados con mocking.
- **Feature:** peticiones HTTP completas con rutas, middleware, base de datos y JWT real.

La suite cubre autenticación, autorización `401/403`, factories, bcrypt, stock, carrito, checkout, subtotal y eliminación de items. `AuthControllerTest` usa Mockery para simular el proveedor JWT en la prueba unitaria; los Feature tests comprueban la integración real.

Ejecutar toda la suite:

```bash
php artisan test --compact
```

Ejecutar Pest directamente:

```bash
vendor/bin/pest --compact
```

Ejecutar un archivo o filtro:

```bash
php artisan test --compact tests/Feature/CheckoutTest.php
php artisan test --compact --filter="login"
```

No ejecutar `vendor/bin/phpunit` directamente: las pruebas están escritas con Pest y deben iniciarse con `php artisan test` o `vendor/bin/pest`.

## Mocking de dependencias externas

El proyecto no realiza llamadas HTTP, pagos ni envíos de correo externos actualmente. `AuthControllerTest` usa Mockery para simular `JWTAuth::attempt()` y devolver un token de prueba. De esta forma se prueba el contrato del controlador sin depender de la firma criptográfica real.

Los Feature tests mantienen JWT real para validar la integración completa de login, middleware y rutas protegidas.

## Postman

Importar [DocumentacionPostman.postman_collection.json](DocumentacionPostman.postman_collection.json) en Postman.

Orden recomendado:

1. Crear usuario o utilizar uno existente.
2. Ejecutar **Iniciar sesión y guardar token**.
3. Crear o consultar categorías y productos.
4. Obtener el carrito autenticado.
5. Agregar, actualizar o eliminar items.
6. Ejecutar checkout.

La colección guarda el JWT en `jwt_token` y lo aplica automáticamente a carritos, items y compras.

## Estructura de carpetas

```text
app/                 Código de aplicación
bootstrap/           Registro de rutas y middleware
config/              Configuración Laravel y JWT
database/            Migraciones, factories y seeders
public/              Punto de entrada HTTP
resources/            Vistas Blade, CSS y JavaScript
routes/               Rutas web y API
storage/              Logs y archivos generados
tests/                Unit y Feature tests
```

## Comandos útiles

```bash
php artisan route:list --path=api
php artisan migrate:status
php artisan db:seed
php artisan config:clear
php artisan cache:clear
```
