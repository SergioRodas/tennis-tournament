import { useState, useEffect } from 'react';

import { useToast } from './use-toast';

import { Player } from '@/domain/player/entities/Player';
import {
  listPlayers,
  PaginationInfo,
} from '@/domain/player/useCases/listPlayers';

export const usePlayers = (initialItemsPerPage: number) => {
  const [players, setPlayers] = useState<Player[]>([]);
  const [pagination, setPagination] = useState<PaginationInfo>({
    totalItems: 0,
    itemsPerPage: initialItemsPerPage,
    currentPage: 1,
    totalPages: 1,
  });
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();

  const fetchPlayers = async (page: number) => {
    setIsLoading(true);
    try {
      const response = await listPlayers(page, pagination.itemsPerPage);
      setPlayers(response.players);
      setPagination(response.pagination);
    } catch (error) {
      console.error('Error al obtener jugadores:', error);
      toast({
        title: 'Error',
        description:
          'No se pudieron cargar los jugadores. Por favor, intente de nuevo.',
        variant: 'destructive',
      });
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchPlayers(pagination.currentPage);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [pagination.currentPage]);

  const handlePageChange = (newPage: number) => {
    setPagination((prev) => ({ ...prev, currentPage: newPage }));
  };

  const handlePlayerCreated = () => {
    fetchPlayers(pagination.currentPage);
    toast({
      title: 'Éxito',
      description: 'Jugador creado correctamente.',
    });
  };

  return {
    players,
    pagination,
    isLoading,
    handlePageChange,
    handlePlayerCreated,
  };
};
