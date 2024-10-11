import React from 'react';

import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
} from '@/components/ui/dialog';
import { Player } from '@/domain/player/entities/Player';

interface TournamentResultDialogProps {
  isOpen: boolean;
  onClose: () => void;
  winner: Player | null;
}

const TournamentResultDialog: React.FC<TournamentResultDialogProps> = ({
  isOpen,
  onClose,
  winner,
}) => {
  if (!winner) return null;

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent className="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>¡Torneo Finalizado!</DialogTitle>
          <DialogDescription>
            El torneo ha sido simulado con éxito.
          </DialogDescription>
        </DialogHeader>
        <div className="py-4">
          <h3 className="text-lg font-semibold mb-2">Ganador del Torneo:</h3>
          <p className="text-2xl font-bold text-green-600">{winner.name}</p>
          <div className="mt-4 space-y-2">
            <p>Nivel de Habilidad: {winner.skillLevel}</p>
            <p>Género: {winner.gender === 'M' ? 'Masculino' : 'Femenino'}</p>
            {winner.gender === 'M' ? (
              <>
                <p>Fuerza: {winner.strength}</p>
                <p>Velocidad: {winner.speed}</p>
              </>
            ) : (
              <p>Tiempo de Reacción: {winner.reactionTime}</p>
            )}
          </div>
        </div>
        <DialogFooter>
          <Button onClick={onClose}>Cerrar</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
};

export default TournamentResultDialog;
