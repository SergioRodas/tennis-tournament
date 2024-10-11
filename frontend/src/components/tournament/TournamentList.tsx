import { ChevronLeft, Trophy, Calendar, Users } from 'lucide-react';
import React from 'react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Spinner } from '@/components/ui/spinner';
import { useTournaments } from '@/presentation/hooks/useTournaments';

interface TournamentListProps {
  onBack: () => void;
}

const TournamentList: React.FC<TournamentListProps> = ({ onBack }) => {
  const {
    tournaments,
    isLoading,
    totalPages,
    currentPage,
    paginate,
    handleViewMatchups,
  } = useTournaments(20);

  return (
    <div className="container mx-auto px-4 py-8">
      <Button
        onClick={onBack}
        variant="outline"
        className="mb-6 flex items-center"
      >
        <ChevronLeft className="mr-2 h-4 w-4" /> Volver
      </Button>
      <h2 className="text-3xl font-bold mb-6 text-center text-gray-800">
        Lista de Torneos
      </h2>
      {isLoading ? (
        <div className="flex justify-center items-center h-64">
          <Spinner className="w-12 h-12 text-blue-600" />
        </div>
      ) : (
        <>
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-8">
            {tournaments.map((tournament) => (
              <Card
                key={tournament.id}
                className="overflow-hidden transition-shadow duration-300 hover:shadow-lg"
              >
                <CardHeader className="bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                  <CardTitle>{tournament.name}</CardTitle>
                </CardHeader>
                <CardContent className="pt-4">
                  <div className="space-y-2">
                    <Badge
                      variant={
                        tournament.gender === 'M' ? 'default' : 'secondary'
                      }
                      className="mb-2"
                    >
                      {tournament.gender === 'M' ? 'Masculino' : 'Femenino'}
                    </Badge>
                    <p className="flex items-center text-sm text-gray-600">
                      <Trophy className="mr-2 h-4 w-4" />
                      Ganador:{' '}
                      {tournament.winner
                        ? tournament.winner.name
                        : 'No determinado'}
                    </p>
                    <p className="flex items-center text-sm text-gray-600">
                      <Calendar className="mr-2 h-4 w-4" />
                      Creado:{' '}
                      {new Date(tournament.created_at).toLocaleDateString()}
                    </p>
                    <p className="flex items-center text-sm text-gray-600">
                      <Users className="mr-2 h-4 w-4" />
                      Estado:{' '}
                      {tournament.finished_at
                        ? `Finalizado el ${new Date(tournament.finished_at).toLocaleDateString()}`
                        : 'En curso'}
                    </p>
                  </div>
                  <Button
                    onClick={() => handleViewMatchups(tournament.id)}
                    variant="outline"
                    className="mt-4 w-full"
                  >
                    Ver enfrentamientos
                  </Button>
                </CardContent>
              </Card>
            ))}
          </div>
          <div className="flex justify-center space-x-2">
            {Array.from({ length: totalPages }, (_, i) => (
              <Button
                key={i}
                onClick={() => paginate(i + 1)}
                variant={currentPage === i + 1 ? 'default' : 'outline'}
                className="w-10 h-10 p-0"
              >
                {i + 1}
              </Button>
            ))}
          </div>
        </>
      )}
    </div>
  );
};

export default TournamentList;
