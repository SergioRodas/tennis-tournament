import { ChevronLeft, Trophy, Users, Swords } from 'lucide-react';
import React, { useState, useEffect, useMemo } from 'react';

import TournamentResultDialog from './TournamentResultDialog';
import CreatePlayerModal from '../player/CreatePlayerModal';

import { matchupApi } from '@/api/matchup/matchupApi';
import { playerApi } from '@/api/player/playerApi';
import { tournamentApi } from '@/api/tournament/tournamentApi';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { Player } from '@/domain/player/entities/Player';
import { useToast } from '@/presentation/hooks/use-toast';

interface CreateTournamentProps {
  onBack: () => void;
}

const CreateTournament: React.FC<CreateTournamentProps> = ({ onBack }) => {
  const [tournamentName, setTournamentName] = useState('');
  const [gender, setGender] = useState<'M' | 'F'>('M');
  const [tournamentId, setTournamentId] = useState<number | null>(null);
  const [matchups, setMatchups] = useState<any[]>([]);
  const [players, setPlayers] = useState<Player[]>([]);
  const [selectedPlayers, setSelectedPlayers] = useState<
    [number | null, number | null]
  >([null, null]);
  const [isCreatePlayerModalOpen, setIsCreatePlayerModalOpen] = useState(false);
  const [tournamentWinner, setTournamentWinner] = useState<Player | null>(null);
  const [isResultDialogOpen, setIsResultDialogOpen] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [isLoadingMatchups, setIsLoadingMatchups] = useState(false);
  const [isCreatingTournament, setIsCreatingTournament] = useState(false);
  const { toast } = useToast();

  useEffect(() => {
    fetchPlayers();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [gender]);

  const fetchPlayers = async () => {
    setIsLoading(true);
    try {
      const response = await playerApi.listPlayers({ gender });
      setPlayers(response.data.players);
    } catch (error) {
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

  const createTournament = async () => {
    if (!tournamentName.trim()) {
      toast({
        title: 'Error',
        description: 'El nombre del torneo es requerido.',
        variant: 'destructive',
      });
      return;
    }

    setIsCreatingTournament(true);
    try {
      const response = await tournamentApi.createTournament({
        name: tournamentName,
        gender,
      });
      setTournamentId(response.data.tournament.id);
      toast({
        title: 'Éxito',
        description: 'Torneo creado correctamente.',
      });
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

  const createMatchup = async () => {
    if (
      !tournamentId ||
      selectedPlayers[0] === null ||
      selectedPlayers[1] === null
    )
      return;

    setIsLoading(true);
    try {
      await matchupApi.createMatchup({
        tournament_id: tournamentId,
        player1_id: selectedPlayers[0],
        player2_id: selectedPlayers[1],
      });
      fetchMatchups();
      setSelectedPlayers([null, null]);
      toast({
        title: 'Éxito',
        description: 'Enfrentamiento creado correctamente.',
      });
    } catch (error) {
      toast({
        title: 'Error',
        description:
          'No se pudo crear el enfrentamiento. Por favor, intente de nuevo.',
        variant: 'destructive',
      });
    } finally {
      setIsLoading(false);
    }
  };

  const fetchMatchups = async () => {
    if (!tournamentId) return;

    setIsLoadingMatchups(true);
    try {
      const response = await matchupApi.listMatchupsByTournament(tournamentId);
      setMatchups(response.data.matchups);
    } catch (error) {
      toast({
        title: 'Error',
        description:
          'No se pudieron cargar los enfrentamientos. Por favor, intente de nuevo.',
        variant: 'destructive',
      });
    } finally {
      setIsLoadingMatchups(false);
    }
  };

  const simulateTournament = async () => {
    if (!tournamentId) return;

    setIsLoading(true);
    try {
      const response = await tournamentApi.simulateTournament(tournamentId);
      setTournamentWinner(response.data.winner);
      setIsResultDialogOpen(true);
    } catch (error) {
      toast({
        title: 'Error',
        description:
          'No se pudo simular el torneo. Por favor, intente de nuevo.',
        variant: 'destructive',
      });
    } finally {
      setIsLoading(false);
    }
  };

  const canSimulate =
    matchups.length >= 2 && (matchups.length & (matchups.length - 1)) === 0;
  const canCreateMatchup =
    selectedPlayers[0] !== null &&
    selectedPlayers[1] !== null &&
    selectedPlayers[0] !== selectedPlayers[1];

  const handlePlayerCreated = () => {
    setIsCreatePlayerModalOpen(false);
    fetchPlayers();
  };

  const handlePlayerSelection = (index: 0 | 1, playerId: number | null) => {
    setSelectedPlayers((prev) => {
      const newSelection: [number | null, number | null] = [...prev];
      newSelection[index] = playerId;
      return newSelection;
    });
  };

  const usedPlayerIds = useMemo(() => {
    const ids = new Set<number>();
    matchups.forEach((matchup) => {
      ids.add(matchup.player1.id);
      ids.add(matchup.player2.id);
    });
    return ids;
  }, [matchups]);

  const isLoadingAny = isLoading || isLoadingMatchups;

  return (
    <div className="container mx-auto px-4 py-8 max-w-4xl">
      <h1 className="text-4xl font-bold text-center text-gray-800">
        Gestión de Torneos
      </h1>
      <Button
        onClick={onBack}
        variant="outline"
        className="mb-6 flex items-center"
        disabled={isLoading}
      >
        <ChevronLeft className="mr-2 h-4 w-4" /> Volver
      </Button>
      <Card className="relative overflow-hidden">
        <CardHeader className="bg-gradient-to-r from-blue-500 to-purple-600 text-white">
          <CardTitle className="text-2xl">
            {!tournamentId ? 'Crear Nuevo Torneo' : 'Crear Enfrentamientos'}
          </CardTitle>
        </CardHeader>
        <CardContent className="p-6">
          <Button
            onClick={() => setIsCreatePlayerModalOpen(true)}
            variant="outline"
            className="absolute top-4 right-4 z-10"
            disabled={isLoading}
          >
            <Users className="mr-2 h-4 w-4" /> Crear Nuevo Jugador
          </Button>
          {isLoading && (
            <div className="absolute inset-0 bg-white bg-opacity-50 flex items-center justify-center z-20">
              <Spinner className="w-12 h-12 text-blue-600" />
            </div>
          )}
          {!tournamentId ? (
            <div className="space-y-6 flex flex-col items-center mt-4">
              <Input
                type="text"
                placeholder="Nombre del Torneo"
                value={tournamentName}
                onChange={(e) => setTournamentName(e.target.value)}
                className="w-full max-w-md px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder:text-gray-400 placeholder:text-sm"
                disabled={isLoadingAny || isCreatingTournament}
              />
              <Select
                value={gender}
                onValueChange={(value: 'M' | 'F') => setGender(value)}
                disabled={isLoadingAny || isCreatingTournament}
              >
                <SelectTrigger className="w-full max-w-md">
                  <SelectValue placeholder="Seleccionar Género" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="M">Masculino</SelectItem>
                  <SelectItem value="F">Femenino</SelectItem>
                </SelectContent>
              </Select>
              <Button
                onClick={createTournament}
                variant="default"
                disabled={
                  isLoadingAny || isCreatingTournament || !tournamentName.trim()
                }
                className="relative w-full max-w-md"
              >
                {isCreatingTournament && (
                  <Spinner className="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5" />
                )}
                <span className={isCreatingTournament ? 'pl-8' : ''}>
                  Crear Torneo
                </span>
              </Button>
            </div>
          ) : (
            <div className="space-y-8 mt-4">
              <div className="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <Select
                  value={selectedPlayers[0]?.toString() || ''}
                  onValueChange={(value) =>
                    handlePlayerSelection(0, value ? Number(value) : null)
                  }
                  disabled={isLoadingAny}
                >
                  <SelectTrigger className="w-full sm:w-[200px]">
                    <SelectValue placeholder="Seleccionar Jugador 1" />
                  </SelectTrigger>
                  <SelectContent>
                    {players.map((player) => (
                      <SelectItem
                        key={player.id}
                        value={player.id.toString()}
                        disabled={
                          player.id === selectedPlayers[1] ||
                          usedPlayerIds.has(player.id)
                        }
                      >
                        {player.name}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
                <Select
                  value={selectedPlayers[1]?.toString() || ''}
                  onValueChange={(value) =>
                    handlePlayerSelection(1, value ? Number(value) : null)
                  }
                  disabled={isLoadingAny}
                >
                  <SelectTrigger className="w-full sm:w-[200px]">
                    <SelectValue placeholder="Seleccionar Jugador 2" />
                  </SelectTrigger>
                  <SelectContent>
                    {players.map((player) => (
                      <SelectItem
                        key={player.id}
                        value={player.id.toString()}
                        disabled={
                          player.id === selectedPlayers[0] ||
                          usedPlayerIds.has(player.id)
                        }
                      >
                        {player.name}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
              <div className="flex justify-center">
                <Button
                  onClick={createMatchup}
                  disabled={!canCreateMatchup || isLoadingAny}
                  variant="default"
                  className="w-full max-w-md"
                >
                  <Swords className="mr-2 h-4 w-4" /> Crear Enfrentamiento
                </Button>
              </div>
              <div className="mt-8 relative">
                <h3 className="text-xl font-semibold mb-4 text-center">
                  Enfrentamientos creados: {matchups.length}
                </h3>
                {isLoadingMatchups ? (
                  <Spinner className="mx-auto" />
                ) : (
                  <div className="grid gap-4 grid-cols-1 sm:grid-cols-2">
                    {matchups.map((matchup, index) => (
                      <Card key={index} className="p-4 text-center bg-gray-50">
                        <p className="font-semibold mb-2">
                          Enfrentamiento {index + 1}
                        </p>
                        <Badge variant="secondary" className="mb-1">
                          {matchup.player1.name}
                        </Badge>
                        <p className="text-sm text-gray-500 my-1">vs</p>
                        <Badge variant="secondary">
                          {matchup.player2.name}
                        </Badge>
                      </Card>
                    ))}
                  </div>
                )}
              </div>
              <div className="flex flex-col items-center">
                <Button
                  onClick={simulateTournament}
                  disabled={!canSimulate || isLoadingAny}
                  variant="default"
                  className="mt-4 w-full max-w-md"
                >
                  <Trophy className="mr-2 h-4 w-4" /> Simular Torneo
                </Button>
                {!canSimulate && (
                  <p className="text-sm text-gray-500 text-center mt-2 max-w-md">
                    Debes crear una cantidad válida de enfrentamientos para
                    iniciar la simulación (2, 4, 8, 16, etc.).
                  </p>
                )}
              </div>
            </div>
          )}
        </CardContent>
      </Card>
      <TournamentResultDialog
        isOpen={isResultDialogOpen}
        onClose={() => {
          setIsResultDialogOpen(false);
          onBack();
        }}
        winner={tournamentWinner}
      />
      <CreatePlayerModal
        isOpen={isCreatePlayerModalOpen}
        onClose={() => setIsCreatePlayerModalOpen(false)}
        onPlayerCreated={handlePlayerCreated}
      />
    </div>
  );
};

export default CreateTournament;
