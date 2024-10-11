import apiClient from '../apiClient';

import { API_URLS } from '@/config/urls';

export const tournamentApi = {
  createTournament: (tournamentData: any) =>
    apiClient.post(API_URLS.TOURNAMENT_CREATE, tournamentData),

  getTournament: (id: number) =>
    apiClient.get(`${API_URLS.TOURNAMENT_GET}${id}`),

  simulateTournament: (tournamentId: number) =>
    apiClient.post(API_URLS.TOURNAMENT_SIMULATE, {
      tournament_id: tournamentId,
    }),

  listTournaments: (params?: any) =>
    apiClient.get(API_URLS.TOURNAMENT_LIST, { params }),
};
