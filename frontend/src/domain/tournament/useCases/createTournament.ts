import { tournamentApi } from '@/api/tournament/tournamentApi';

export const createTournament = async (
  name: string,
  gender: 'M' | 'F'
): Promise<number> => {
  const response = await tournamentApi.createTournament({ name, gender });
  return response.data.tournament.id;
};
