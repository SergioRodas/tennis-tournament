import React from 'react';

import Layout from '../components/Layout';

import MatchupList from '@/components/matchup/MatchupList';
import TournamentSelector from '@/components/matchup/TournamentSelector';
import { Card, CardContent } from '@/components/ui/card';
import { Spinner } from '@/components/ui/spinner';
import { useMatchups } from '@/presentation/hooks/useMatchups';

const MatchupPage: React.FC = () => {
  const {
    tournaments,
    selectedTournament,
    matchups,
    isLoading,
    handleTournamentChange,
    getMessageForNoMatchups,
  } = useMatchups();

  return (
    <Layout>
      <div className="container mx-auto px-4 py-8 flex flex-col items-center">
        <h1 className="text-4xl font-bold mb-6 text-gray-800">
          Enfrentamientos
        </h1>
        <TournamentSelector
          tournaments={tournaments}
          selectedTournament={selectedTournament}
          onTournamentChange={handleTournamentChange}
          isLoading={isLoading}
        />
        {isLoading ? (
          <div className="flex justify-center items-center h-64">
            <Spinner className="w-12 h-12 text-blue-600" />
          </div>
        ) : matchups.length > 0 ? (
          <MatchupList matchups={matchups} />
        ) : (
          <Card className="w-full max-w-md">
            <CardContent className="p-6">
              <p className="text-center text-gray-600">
                {getMessageForNoMatchups()}
              </p>
            </CardContent>
          </Card>
        )}
      </div>
    </Layout>
  );
};

export default MatchupPage;
