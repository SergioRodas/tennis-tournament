import apiClient from '../apiClient';

import { API_URLS } from '@/config/urls';

export const playerApi = {
  createPlayer: (playerData: any) =>
    apiClient.post(API_URLS.PLAYER_CREATE, playerData),

  getPlayer: (id: number) => apiClient.get(`${API_URLS.PLAYER_GET}${id}`),

  listPlayers: (params?: any) =>
    apiClient.get(API_URLS.PLAYER_LIST, { params }),
};
