import { BrowserRouter, Route, Routes } from 'react-router-dom';

import { ROUTES } from './config/urls';
import HomePage from './pages/HomePage';
import MatchupPage from './pages/MatchupPage';
import PlayerPage from './pages/PlayerPage';
import TournamentPage from './pages/TournamentPage';

import { Toaster } from '@/components/ui/toaster';

export const Router = () => {
  return (
    <BrowserRouter>
      <Toaster />
      <Routes>
        <Route path={ROUTES.HOME} element={<HomePage />} />
        <Route path={ROUTES.PLAYERS} element={<PlayerPage />} />
        <Route path={ROUTES.MATCHUPS} element={<MatchupPage />} />
        <Route path={ROUTES.TOURNAMENTS} element={<TournamentPage />} />
      </Routes>
    </BrowserRouter>
  );
};

export default Router;
