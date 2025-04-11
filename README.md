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