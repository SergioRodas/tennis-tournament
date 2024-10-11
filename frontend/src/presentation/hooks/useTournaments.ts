import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

import { useToast } from './use-toast';

import { Tournament } from '@/domain/tournament/entities/Tournament';
import { createTournament } from '@/domain/tournament/useCases/createTournament';
import { getTournaments } from '@/domain/tournament/useCases/getTournaments';

export const useTournaments = (itemsPerPage: number) => {
  const [tournaments, setTournaments] = useState<Tournament[]>([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [isLoading, setIsLoading] = useState(false);
  const [isCreatingTournament, setIsCreatingTournament] = useState(false);
  const { toast } = useToast();
  const navigate = useNavigate();

  useEffect(() => {
    fetchTournaments();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

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

  const handleCreateTournament = async (name: string, gender: 'M' | 'F') => {
    setIsCreatingTournament(true);
    try {
      await createTournament(name, gender);
      toast({
        title: 'Éxito',
        description: 'Torneo creado correctamente.',
      });
      fetchTournaments();
    } catch (error) {
      toast({
        title: 'Error',
        description: 'No se pudo crear el torneo. Por favor, intente de nuevo.',
        variant: 'destructive',
      });
    } finally {
      setIsCreatingTournament(false);
    }
  };

  const indexOfLastTournament = currentPage * itemsPerPage;
  const indexOfFirstTournament = indexOfLastTournament - itemsPerPage;
  const currentTournaments = tournaments.slice(
    indexOfFirstTournament,
    indexOfLastTournament
  );

  const totalPages = Math.ceil(tournaments.length / itemsPerPage);

  const paginate = (pageNumber: number) => setCurrentPage(pageNumber);

  const handleViewMatchups = (tournamentId: number) => {
    navigate(`/matchups?tournamentId=${tournamentId}`);
  };

  return {
    tournaments: currentTournaments,
    isLoading,
    isCreatingTournament,
    totalPages,
    currentPage,
    handleCreateTournament,
    paginate,
    handleViewMatchups,
  };
};
