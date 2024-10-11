import React from 'react';

import Layout from '../components/Layout';
import PlayerList from '../components/player/PlayerList';

const PlayerPage: React.FC = () => {
  return (
    <Layout>
      <PlayerList />
    </Layout>
  );
};

export default PlayerPage;
