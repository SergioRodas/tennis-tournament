import React, { useState, useEffect } from 'react';

import ModalWrapper from '../ui/modal-wrapper';

import { playerApi } from '@/api/player/playerApi';
import { Button } from '@/components/ui/button';
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

interface CreatePlayerModalProps {
  isOpen: boolean;
  onClose: () => void;
  onPlayerCreated: () => void;
}

const CreatePlayerModal: React.FC<CreatePlayerModalProps> = ({
  isOpen,
  onClose,
  onPlayerCreated,
}) => {
  const [player, setPlayer] = useState<Partial<Player>>({
    name: '',
    skillLevel: 0,
    gender: '',
    strength: undefined,
    speed: undefined,
    reactionTime: undefined,
  });
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();

  useEffect(() => {
    if (player.gender === 'F') {
      setPlayer((prev) => ({
        ...prev,
        strength: undefined,
        speed: undefined,
        reactionTime: 0,
      }));
    } else if (player.gender === 'M') {
      setPlayer((prev) => ({
        ...prev,
        reactionTime: undefined,
        strength: 0,
        speed: 0,
      }));
    }
  }, [player.gender]);

  const handleChange = (field: keyof Player, value: string | number) => {
    setPlayer((prev) => ({ ...prev, [field]: value }));
  };

  const isFormValid = () => {
    if (!player.name || !player.skillLevel || !player.gender) return false;
    if (
      player.gender === 'M' &&
      (player.strength === undefined || player.speed === undefined)
    )
      return false;
    if (player.gender === 'F' && player.reactionTime === undefined)
      return false;
    return true;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!isFormValid()) return;

    setIsLoading(true);
    try {
      await playerApi.createPlayer(player);
      onPlayerCreated();
      setPlayer({
        name: '',
        skillLevel: 0,
        gender: '',
        strength: undefined,
        speed: undefined,
        reactionTime: undefined,
      });
      toast({
        title: 'Éxito',
        description: 'Jugador creado correctamente.',
      });
      onClose();
    } catch (error) {
      console.error('Error al crear jugador:', error);
      toast({
        title: 'Error',
        description:
          'No se pudo crear el jugador. Por favor, intente de nuevo.',
        variant: 'destructive',
      });
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <ModalWrapper isOpen={isOpen} onClose={onClose}>
      <h2 className="text-xl font-bold mb-4">Crear Nuevo Jugador</h2>
      <form onSubmit={handleSubmit}>
        <div className="mb-4">
          <Input
            type="text"
            value={player.name}
            onChange={(e) => handleChange('name', e.target.value)}
            placeholder="Nombre"
            required
          />
        </div>
        <div className="mb-4">
          <Input
            type="number"
            value={player.skillLevel || ''}
            onChange={(e) =>
              handleChange('skillLevel', parseInt(e.target.value))
            }
            placeholder="Nivel de Habilidad"
            required
            min="1"
            max="100"
          />
        </div>
        <div className="mb-4">
          <Select
            value={player.gender}
            onValueChange={(value) => handleChange('gender', value)}
          >
            <SelectTrigger>
              <SelectValue placeholder="Seleccionar Género" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="M">Masculino</SelectItem>
              <SelectItem value="F">Femenino</SelectItem>
            </SelectContent>
          </Select>
        </div>
        {player.gender === 'M' && (
          <>
            <div className="mb-4">
              <Input
                type="number"
                value={player.strength || ''}
                onChange={(e) =>
                  handleChange('strength', parseInt(e.target.value))
                }
                placeholder="Fuerza"
                required
                min="0"
                max="100"
              />
            </div>
            <div className="mb-4">
              <Input
                type="number"
                value={player.speed || ''}
                onChange={(e) =>
                  handleChange('speed', parseInt(e.target.value))
                }
                placeholder="Velocidad"
                required
                min="0"
                max="100"
              />
            </div>
          </>
        )}
        {player.gender === 'F' && (
          <div className="mb-4">
            <Input
              type="number"
              value={player.reactionTime || ''}
              onChange={(e) =>
                handleChange('reactionTime', parseFloat(e.target.value))
              }
              placeholder="Tiempo de Reacción"
              required
              min="0"
              max="100"
              step="0.1"
            />
          </div>
        )}
        <div className="flex justify-end">
          <Button
            type="button"
            onClick={onClose}
            className="mr-2"
            disabled={isLoading}
          >
            Cancelar
          </Button>
          <Button type="submit" disabled={!isFormValid() || isLoading}>
            {isLoading ? <Spinner className="mr-2" /> : null}
            Crear
          </Button>
        </div>
      </form>
    </ModalWrapper>
  );
};

export default CreatePlayerModal;
