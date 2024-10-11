import { Trophy, List } from 'lucide-react';
import React, { useState } from 'react';

import Layout from '../components/Layout';
import CreateTournament from '../components/tournament/CreateTournament';
import TournamentList from '../components/tournament/TournamentList';

import { Button } from '@/components/ui/button';

const TournamentPage: React.FC = () => {
  const [view, setView] = useState<'select' | 'create' | 'list'>('select');

  const renderContent = () => {
    switch (view) {
      case 'create':
        return <CreateTournament onBack={() => setView('select')} />;
      case 'list':
        return <TournamentList onBack={() => setView('select')} />;
      default:
        return (
          <div className="flex flex-col items-center justify-center min-h-[60vh] space-y-8 p-8">
            <h1 className="text-4xl font-bold text-blue-800 mb-6">
              Gestión de Torneos
            </h1>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <Button
                onClick={() => setView('create')}
                className="flex items-center justify-center space-x-2 bg-green-600 hover:bg-green-700 text-white py-4 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105"
              >
                <Trophy size={24} />
                <span>Crear un torneo</span>
              </Button>
              <Button
                onClick={() => setView('list')}
                className="flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105"
              >
                <List size={24} />
                <span>Ver torneos</span>
              </Button>
            </div>
          </div>
        );
    }
  };

  return (
    <Layout>
      <div className="container mx-auto p-4">{renderContent()}</div>
    </Layout>
  );
};

export default TournamentPage;
