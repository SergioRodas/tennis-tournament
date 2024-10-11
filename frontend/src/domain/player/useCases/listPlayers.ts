import { Player } from '../entities/Player';

import { playerApi } from '@/api/player/playerApi';

export interface PaginationInfo {
  totalItems: number;
  itemsPerPage: number;
  currentPage: number;
  totalPages: number;
}

export interface ListPlayersResponse {
  players: Player[];
  pagination: PaginationInfo;
}

export const listPlayers = async (
  page: number,
  limit: number
): Promise<ListPlayersResponse> => {
  const response = await playerApi.listPlayers({ page, limit });
  return response.data;
};
