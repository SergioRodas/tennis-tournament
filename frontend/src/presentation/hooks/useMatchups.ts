import { useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';

import { useToast } from './use-toast';

import { Matchup } from '@/domain/matchup/entities/Matchup';
import { getMatchupsByTournament } from '@/domain/matchup/useCases/getMatchupsByTournament';
import { Tournament } from '@/domain/tournament/entities/Tournament';
import { getTournaments } from '@/domain/tournament/useCases/getTournaments';

export const useMatchups = () => {
  const [tournaments, setTournaments] = useState<Tournament[]>([]);
  const [selectedTournament, setSelectedTournament] = useState<number | null>(
    null
  );
  const [matchups, setMatchups] = useState<Matchup[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();
  const location = useLocation();

  useEffect(() => {
    fetchTournaments();
    const params = new URLSearchParams(location.search);
    const tournamentId = params.get('tournamentId');
    if (tournamentId) {
      setSelectedTournament(Number(tournamentId));
      fetchMatchups(Number(tournamentId));
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [location]);

  const fetchTournaments = async () => {
    setIsLoading(true);
    try {
      const data = await getTournaments();
      setTournaments(data);
    } catch (error) {
      toast({
        title: 'Error',
        description:
          'No se pudieron cargar los torneos. Por favor, intente de nuevo.',
        variant: 'destructive',
      });
    } finally {
      setIsLoading(false);
    }
  };

  const fetchMatchups = async (tournamentId: number) => {
    setIsLoading(true);
    try {
      const data = await getMatchupsByTournament(tournamentId);
      setMatchups(data);
    } catch (error: any) {
      setMatchups([]);
      if (error.response && error.response.status !== 404) {
        toast({
          title: 'Error',
          description:
            'No se pudieron cargar los enfrentamientos. Por favor, intente de nuevo.',
          variant: 'destructive',
        });
      }
    } finally {
      setIsLoading(false);
    }
  };

  const handleTournamentChange = (value: string) => {
    const tournamentId = Number(value);
    setSelectedTournament(tournamentId);
    fetchMatchups(tournamentId);
  };

  const getMessageForNoMatchups = () => {
    if (selectedTournament === null) {
      return 'Elige un torneo existente para poder ver el historial de enfrentamientos';
    }
    return 'Este torneo no tiene enfrentamientos aún.';
  };

  return {
    tournaments,
    selectedTournament,
    matchups,
    isLoading,
    handleTournamentChange,
    getMessageForNoMatchups,
  };
};
