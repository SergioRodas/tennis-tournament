// URLs del Backend
export const API_URLS = {
  BASE_URL: process.env.REACT_APP_SERVER_ENDPOINT || 'http://localhost:8000',

  // Player routes
  PLAYER_CREATE: '/api/player/create',
  PLAYER_GET: '/api/player/',
  PLAYER_LIST: '/api/player/list',

  // Matchup routes
  MATCHUP_CREATE: '/api/matchup/create',
  MATCHUP_GET: '/api/matchup/',
  MATCHUP_UPDATE_WINNER: '/api/matchup/update/',
  MATCHUP_LIST_BY_TOURNAMENT: '/api/matchup/list/',

  // Tournament routes
  TOURNAMENT_CREATE: '/api/tournament/create',
  TOURNAMENT_GET: '/api/tournament/',
  TOURNAMENT_SIMULATE: '/api/tournament/simulate',
  TOURNAMENT_LIST: '/api/tournament/list',
};

// Rutas del Frontend
export const ROUTES = {
  HOME: '/',
  PLAYERS: '/players',
  MATCHUPS: '/matchups',
  TOURNAMENTS: '/tournaments',
};
