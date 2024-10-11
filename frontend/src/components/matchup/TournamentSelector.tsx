import React from 'react';

import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Tournament } from '@/domain/tournament/entities/Tournament';

interface TournamentSelectorProps {
  tournaments: Tournament[];
  selectedTournament: number | null;
  onTournamentChange: (value: string) => void;
  isLoading: boolean;
}

const TournamentSelector: React.FC<TournamentSelectorProps> = ({
  tournaments,
  selectedTournament,
  onTournamentChange,
  isLoading,
}) => {
  return (
    <div className="mb-8 w-full max-w-md">
      <p className="mb-2 font-semibold text-gray-700">Torneo Seleccionado:</p>
      <Select
        value={selectedTournament?.toString()}
        onValueChange={onTournamentChange}
        disabled={isLoading}
      >
        <SelectTrigger className="w-full bg-white border-2 border-gray-300 rounded-lg">
          <SelectValue placeholder="Selecciona un Torneo" />
        </SelectTrigger>
        <SelectContent>
          {tournaments.map((tournament) => (
            <SelectItem key={tournament.id} value={tournament.id.toString()}>
              {tournament.name}
            </SelectItem>
          ))}
        </SelectContent>
      </Select>
    </div>
  );
};

export default TournamentSelector;
