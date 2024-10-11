# Tennis Tournament Backend

## Descripción

Este es el backend para la aplicación Tennis Tournament. Está construido con Symfony y proporciona una API RESTful para manejar jugadores, torneos y enfrentamientos de tenis.

## Tecnologías Utilizadas

- PHP 8.1+
- Symfony 6.3
- Doctrine ORM
- MySQL
- Docker

## Estructura del Proyecto

```
backend/src/
├── Api/
│   ├── Matchup/
│   │   ├── Application/
│   │   │   ├── Dto/
│   │   │   └── UseCases/
│   │   ├── Domain/
│   │   └── Infrastructure/
│   │       ├── Controller/
│   │       └── Persistence/
│   │           └── Doctrine/
│   ├── Player/
│   │   ├── Application/
│   │   │   ├── Dto/
│   │   │   └── UseCases/
│   │   ├── Domain/
│   │   └── Infrastructure/
│   │       ├── Controller/
│   │       └── Persistence/
│   │           └── Doctrine/
│   └── Tournament/
│       ├── Application/
│       │   ├── Dto/
│       │   └── UseCases/
│       ├── Domain/
│       └── Infrastructure/
│           ├── Controller/
│           └── Persistence/
│               └── Doctrine/
└── Shared/
    ├── Domain/
    │   └── Exception/
    └── Infrastructure/
```

## Configuración

1. Crea un archivo `.env` en la raíz del proyecto backend con el siguiente contenido:

   ```
   APP_ENV=dev
   APP_SECRET=bc1f258eac1248ad3d10298f660ef3d2
   DATABASE_URL="mysql://symfony:symfony_password@db:3306/symfony_db?serverVersion=8.0"
   DATABASE_TEST_URL="mysql://symfony:symfony_password@db:3306/symfony_test_db?serverVersion=8.0"
   CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$'
   ```

   Asegúrate de ajustar los valores según tu configuración local si es necesario.

2. Crea un archivo `.env.test` en la raíz del proyecto backend con el siguiente contenido:

   ```
   KERNEL_CLASS='App\Kernel'
   APP_SECRET='$ecretf0rt3st'
   SYMFONY_DEPRECATIONS_HELPER=999999
   PANTHER_APP_ENV=panther
   PANTHER_ERROR_SCREENSHOT_DIR=./var/error-screenshots
   DATABASE_URL="mysql://symfony:symfony_password@db:3306/symfony_test_db?serverVersion=8.0"
   ```

3. Instala las dependencias:
   ```
   composer install
   ```

4. Crea la base de datos:
   ```
   php bin/console doctrine:database:create
   ```

5. Ejecuta las migraciones:
   ```
   php bin/console doctrine:migrations:migrate
   ```

## Ejecución

Para ejecutar el servidor de desarrollo de Symfony:
   ```
   php bin/console server:run
   ```

## Tests

Para ejecutar los tests, puedes usar los siguientes comandos:

- Para ejecutar todos los tests:
  ```
  ./vendor/bin/phpunit --testsuite All
  ```

- Para ejecutar solo los tests de integración:
  ```
  ./vendor/bin/phpunit --testsuite Integration
  ```

- Para ejecutar solo los tests unitarios:
  ```
  ./vendor/bin/phpunit --testsuite Unit
  ```

Estos comandos te permiten ejecutar selectivamente diferentes conjuntos de tests según tus necesidades de desarrollo o integración continua.

## API Endpoints

### Documentación
- `GET /api/doc`: Swagger UI para la documentación de la API
- `GET /api/doc.json`: Documentación de la API en formato JSON

### Jugadores (Players)
- `POST /api/player/create`: Crea un nuevo jugador
- `GET /api/player/{id}`: Obtiene un jugador específico
- `GET /api/player/list`: Lista todos los jugadores

### Enfrentamientos (Matchups)
- `POST /api/matchup/create`: Crea un nuevo enfrentamiento
- `GET /api/matchup/{id}`: Obtiene un enfrentamiento específico
- `PUT /api/matchup/update/{id}/winner`: Actualiza el ganador de un enfrentamiento
- `GET /api/matchup/list/{tournamentId}`: Lista los enfrentamientos de un torneo específico

### Torneos (Tournaments)
- `POST /api/tournament/create`: Crea un nuevo torneo
- `GET /api/tournament/{id}`: Obtiene un torneo específico
- `POST /api/tournament/simulate`: Simula un torneo
- `GET /api/tournament/list`: Lista todos los torneos

## Contribuir

Si deseas contribuir al proyecto, por favor:

1. Haz un fork del repositorio
2. Crea una nueva rama (`git checkout -b feature/AmazingFeature`)
3. Haz commit de tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Haz push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo `LICENSE` para más detalles.