# Tennis Tournament Frontend

## Descripción

Este es el frontend para la aplicación Tennis Tournament. Está construido con React y proporciona una interfaz de usuario interactiva para manejar jugadores, torneos y enfrentamientos de tenis.

## Documentación general del proyecto

Para una visión general del proyecto, incluyendo la configuración inicial y la ejecución de tests, por favor consulte el [README principal](../README.md) en la raíz del proyecto.

## Tecnologías Utilizadas

- React
- TypeScript
- Vite
- React Router
- Axios (para llamadas a la API)

## Estructura del Proyecto

```
frontend/src/
├── api/
│   ├── matchup/
│   ├── player/
│   └── tournament/
├── assets/
├── components/
│   ├── matchup/
│   ├── player/
│   ├── tournament/
│   └── ui/
├── config/
├── domain/
│   ├── matchup/
│   ├── player/
│   └── tournament/
├── lib/
├── pages/
└── presentation/
    ├── hooks/
    └── store/
```

## Configuración

1. Instala las dependencias:
   ```
   npm install
   ```

2. Crea un archivo `.env` en la raíz del proyecto frontend con el siguiente contenido:
   ```
   REACT_APP_SERVER_ENDPOINT=http://localhost:8000
   ```
   Ajusta la URL según la configuración de tu backend.

## Ejecución

Para ejecutar el servidor de desarrollo:
```
npm run dev
```

Para construir el proyecto para producción:
```
npm run build
```

## Componentes Principales

- `Layout.tsx`: Componente principal que envuelve toda la aplicación.
- `PlayerList.tsx`: Muestra la lista de jugadores.
- `TournamentList.tsx`: Muestra la lista de torneos.
- `MatchupList.tsx`: Muestra la lista de enfrentamientos.

## Páginas

- `HomePage.tsx`: Página de inicio.
- `PlayerPage.tsx`: Página para gestionar jugadores.
- `TournamentPage.tsx`: Página para gestionar torneos.
- `MatchupPage.tsx`: Página para ver enfrentamientos.

## API

La comunicación con el backend se maneja a través de los archivos en la carpeta `api/`:

- `playerApi.ts`
- `tournamentApi.ts`
- `matchupApi.ts`

## Hooks Personalizados

Se han creado varios hooks personalizados para manejar la lógica de negocio:

- `usePlayers.ts`
- `useTournaments.ts`
- `useMatchups.ts`

## Contribuir

Si deseas contribuir al proyecto, por favor:

1. Haz un fork del repositorio
2. Crea una nueva rama (`git checkout -b feature/AmazingFeature`)
3. Haz commit de tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Haz push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo `LICENSE` para más detalles.
