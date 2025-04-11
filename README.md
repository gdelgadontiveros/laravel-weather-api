# Weather API con Laravel 12

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Swagger](https://img.shields.io/badge/Swagger-85EA2D?style=for-the-badge&logo=Swagger&logoColor=black)

API en Laravel 12 para la gestion de usuarios, consultar datos climáticos desde WeatherAPI y documentada con Swagger.

## Características Principales

- ✅ Autenticación con Laravel Sanctum
- ✅ Gestion de Usuarios
- ✅ Integración con WeatherAPI para la consulta del clima, manejo de historial y favoritos
- ✅ RateLimit para el control de peticiones a la api
- ✅ Documentación con Swagger
- ✅ Arquitectura escalable
- ✅ Buenas prácticas de seguridad
- ✅ Testing

## Requisitos Técnicos

- PHP 8.2+
- Laravel 12
- Composer 2.7+
- Redis (opcional para caché)
- Base de datos de su preferencia (para este proyecto se utilizo -> Postgresql)

## Instalación

1. Clonar repositorio:
```bash
git clone https://github.com/gdelgadontiveros/laravel-weather-api.git
cd laravel-weather-api
```

2. Instalar de dependencias:
```bash
composer install
```

3. Configurar entorno:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configurar variables de entorno:
```bash

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=weatherapi
DB_USERNAME=weatherapi
DB_PASSWORD=weatherapi*2025

WEATHER_API_KEY=tu_api_key_weatherapi
WEATHER_API_URL=https://api.weatherapi.com/v1
WEATHER_CACHE_TTL=60

API_RATE_LIMIT=60
AUTH_RATE_LIMIT=10
WEATHER_RATE_LIMIT=30

L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST=http://localhost:8000

```

5. Ejecutar migraciones:
```bash
php artisan migrate --seed
```

6. Generar documentación Swagger:
```bash
php artisan l5-swagger:generate
```

## Estructura del Proyecto

```bash
app/
├── Http/                    #
│   ├── Controllers/         #
│           ├── API/V1       # Controladores necesarios ordenados por modelo de datos
│   ├── Middleware/          #
│   └── Requests/            #
│   └── Resources/           #
├── Models/                  # Modelos
├── Providers/               # Configuracion del RateLimitProvider
├── Services/                #
│   ├── Weather/             # Servicio para weatherapi
config/                      # Configuraciones
routes/                      # Definición de rutas
tests/                       # Pruebas automatizadas
```

## Uso de la API

Documentación Swagger cambiar localhost:8000 por url:port de configuración:
```bash
http://localhost:8000/api/documentation
```

## Pruebas
```bash
# Ejecutar todas las pruebas
php artisan test

# Pruebas específicas
php artisan test --filter MissingRouteTest
php artisan test --filter AuthenticationTest
php artisan test --filter UserApiTest
php artisan test --filter WeatherApiTest
```

## Contacto

- Author: Gustavo Delgado
- Twitter: [@gdelgadoCode](https://twitter.com/gdelgadoCode)
- Instagram: [@gustavod88](https://www.instagram.com/gustavod88/)
- Email: gustavo.a.delgado.o@gmail.com
- Enlace del proyecto: https://github.com/gdelgadontiveros/laravel-weather-api.git

