import apiClient from '../apiClient';

import { API_URLS } from '@/config/urls';

export const matchupApi = {
  createMatchup: (matchupData: any) =>
    apiClient.post(API_URLS.MATCHUP_CREATE, matchupData),

  getMatchup: (id: number) => apiClient.get(`${API_URLS.MATCHUP_GET}${id}`),

  updateMatchupWinner: (id: number, winnerId: number) =>
    apiClient.put(`${API_URLS.MATCHUP_UPDATE_WINNER}${id}/winner`, {
      winner_id: winnerId,
    }),

  listMatchupsByTournament: (tournamentId: number, params?: any) =>
    apiClient.get(`${API_URLS.MATCHUP_LIST_BY_TOURNAMENT}${tournamentId}`, {
      params,
    }),
};
