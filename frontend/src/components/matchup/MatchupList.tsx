import { Trophy, Swords } from 'lucide-react';
import React from 'react';

import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Matchup } from '@/domain/matchup/entities/Matchup';

interface MatchupListProps {
  matchups: Matchup[];
}

const MatchupList: React.FC<MatchupListProps> = ({ matchups }) => {
  return (
    <div className="grid gap-6 w-full max-w-3xl">
      {matchups.map((matchup) => (
        <Card
          key={matchup.matchup_id}
          className="overflow-hidden transition-shadow duration-300 hover:shadow-lg"
        >
          <CardHeader className="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-4">
            <CardTitle className="flex items-center">
              <Swords className="mr-2" />
              Enfrentamiento {matchup.matchup_id}
            </CardTitle>
          </CardHeader>
          <CardContent className="p-4">
            <div className="flex justify-between items-center mb-4">
              <Badge variant="outline" className="text-sm px-3 py-1">
                {matchup.player1.name}
              </Badge>
              <span className="text-lg font-bold text-gray-600">VS</span>
              <Badge variant="outline" className="text-sm px-3 py-1">
                {matchup.player2.name}
              </Badge>
            </div>
            {matchup.winner_id && (
              <div className="mt-4 flex items-center justify-center bg-green-100 p-2 rounded-lg">
                <Trophy className="text-yellow-500 mr-2" />
                <span className="font-semibold text-green-700">
                  Ganador:{' '}
                  {matchup.winner_id === matchup.player1.id
                    ? matchup.player1.name
                    : matchup.player2.name}
                </span>
              </div>
            )}
          </CardContent>
        </Card>
      ))}
    </div>
  );
};

export default MatchupList;
