import { Matchup } from '../entities/Matchup';

import { matchupApi } from '@/api/matchup/matchupApi';

export const getMatchupsByTournament = async (
  tournamentId: number
): Promise<Matchup[]> => {
  const response = await matchupApi.listMatchupsByTournament(tournamentId);
  return response.data.matchups;
};
