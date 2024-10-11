import React, { useState } from 'react';

import CreatePlayerModal from './CreatePlayerModal';
import PlayerTable from './PlayerTable';
import Pagination from '../ui/pagination';

import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { usePlayers } from '@/presentation/hooks/usePlayers';

const PlayerList: React.FC = () => {
  const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);
  const {
    players,
    pagination,
    isLoading,
    handlePageChange,
    handlePlayerCreated,
  } = usePlayers(10);

  const handleCreatePlayer = () => {
    setIsCreateModalOpen(true);
  };

  const onPlayerCreated = () => {
    setIsCreateModalOpen(false);
    handlePlayerCreated();
  };

  return (
    <div className="container mx-auto p-4">
      <div className="flex justify-between items-center mb-6">
        <h2 className="text-3xl font-bold text-gray-800 mb-6">
          Lista de Jugadores
        </h2>
        <Button
          onClick={handleCreatePlayer}
          className="bg-blue-600 hover:bg-blue-700"
        >
          Crear Nuevo Jugador
        </Button>
      </div>
      {isLoading ? (
        <div className="flex justify-center items-center h-64">
          <Spinner className="w-10 h-10 text-blue-600" />
        </div>
      ) : (
        <PlayerTable players={players} />
      )}
      <Pagination
        currentPage={pagination.currentPage}
        totalPages={pagination.totalPages}
        onPageChange={handlePageChange}
        isLoading={isLoading}
      />
      <CreatePlayerModal
        isOpen={isCreateModalOpen}
        onClose={() => setIsCreateModalOpen(false)}
        onPlayerCreated={onPlayerCreated}
      />
    </div>
  );
};

export default PlayerList;
