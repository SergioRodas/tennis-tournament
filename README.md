# Simulador de Torneo de Tenis

## Descripción

Este proyecto simula un torneo de tenis con eliminación directa, donde los jugadores compiten en rondas sucesivas hasta que queda un único campeón. El sistema modela tanto torneos masculinos como femeninos, cada uno con sus características únicas.

### Características principales:

- **Eliminación directa**: Los perdedores quedan inmediatamente eliminados, mientras que los ganadores avanzan a la siguiente fase.
- **Torneos por género**: Se pueden simular torneos masculinos y femeninos.
- **Atributos de jugadores**: 
  - Nombre
  - Nivel de habilidad (0-100)
  - Para hombres: fuerza y velocidad de desplazamiento
  - Para mujeres: tiempo de reacción
- **Cálculo de ganadores**: Basado en habilidad y un factor de suerte.
- **Simulación completa**: A partir de una lista de jugadores, se simula el torneo completo hasta determinar un ganador.

## Requisitos previos

- Docker Desktop instalado y en ejecución
- Node.js y npm (para scripts de gestión del proyecto)

## Configuración inicial

1. Clonar el repositorio:
   ```
   git clone [URL_DEL_REPOSITORIO]
   cd [NOMBRE_DEL_DIRECTORIO]
   ```

2. Instalar dependencias del proyecto:
   ```
   cd frontend && npm install && cd ..
   ```

3. Configurar y construir los contenedores Docker:
   ```
   docker-compose up -d --build
   ```

4. Ejecutar las migraciones de la base de datos:
   ```
   docker-compose exec backend php bin/console doctrine:migrations:migrate -n
   ```

5. Configurar la base de datos de prueba:
   ```
   docker-compose exec backend php bin/console doctrine:database:drop --force --env=test
   docker-compose exec backend php bin/console doctrine:database:create --env=test
   docker-compose exec backend php bin/console doctrine:schema:create --env=test
   ```

## Ejecución de tests

Para ejecutar los tests, use los siguientes comandos:

- Todos los tests:
  ```
  docker-compose exec backend ./vendor/bin/phpunit --testsuite All
  ```

- Tests unitarios:
  ```
  docker-compose exec backend ./vendor/bin/phpunit --testsuite Unit
  ```

- Tests de integración:
  ```
  docker-compose exec backend ./vendor/bin/phpunit --testsuite Integration
  ```

## Documentación adicional

Para información más detallada sobre componentes específicos del proyecto, consulte:

- [Documentación del Frontend](./frontend/README.md)
- [Documentación del Backend](./backend/README.md)

## Convenciones de desarrollo

Este proyecto utiliza [Conventional Commits](https://www.conventionalcommits.org/) para el formato de los mensajes de commit, lo que facilita la generación automática de changelogs y la comprensión del historial del proyecto.

## Licencia

[Incluir información de licencia aquí]
