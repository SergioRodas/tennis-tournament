import { Tournament } from '../entities/Tournament';

import { tournamentApi } from '@/api/tournament/tournamentApi';

export const getTournaments = async (): Promise<Tournament[]> => {
  const response = await tournamentApi.listTournaments();
  return response.data;
};
