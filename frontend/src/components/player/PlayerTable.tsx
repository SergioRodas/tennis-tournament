import React from 'react';

import { Player } from '@/domain/player/entities/Player';

interface PlayerTableProps {
  players: Player[];
}

const PlayerTable: React.FC<PlayerTableProps> = ({ players }) => (
  <div className="overflow-x-auto shadow-md rounded-lg">
    <table className="min-w-full bg-white">
      <thead className="bg-gray-100">
        <tr>
          <th className="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Nombre
          </th>
          <th className="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Nivel de Habilidad
          </th>
          <th className="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Género
          </th>
          <th className="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Fuerza
          </th>
          <th className="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Velocidad
          </th>
          <th className="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Tiempo de Reacción
          </th>
          <th className="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Torneos Ganados
          </th>
        </tr>
      </thead>
      <tbody className="bg-white divide-y divide-gray-200">
        {players.map((player) => (
          <tr key={player.id} className="hover:bg-gray-50">
            <td className="py-4 px-4 whitespace-nowrap">{player.name}</td>
            <td className="py-4 px-4 whitespace-nowrap">{player.skillLevel}</td>
            <td className="py-4 px-4 whitespace-nowrap">
              {player.gender === 'M' ? 'Masculino' : 'Femenino'}
            </td>
            <td className="py-4 px-4 whitespace-nowrap">
              {player.strength || '-'}
            </td>
            <td className="py-4 px-4 whitespace-nowrap">
              {player.speed || '-'}
            </td>
            <td className="py-4 px-4 whitespace-nowrap">
              {player.reactionTime || '-'}
            </td>
            <td className="py-4 px-4 whitespace-nowrap">
              {player.tournamentsWon}
            </td>
          </tr>
        ))}
      </tbody>
    </table>
  </div>
);

export default PlayerTable;
