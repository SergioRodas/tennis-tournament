import React from 'react';
import { Link } from 'react-router-dom';

import { ROUTES } from '../config/urls';

const Layout: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  return (
    <div className="min-h-screen bg-gray-100">
      <nav className="bg-white shadow-sm">
        <div className="container mx-auto px-4">
          <div className="flex justify-between items-center py-4">
            <Link to={ROUTES.HOME} className="text-xl font-bold">
              Torneo de Tenis
            </Link>
            <div className="space-x-4">
              <Link
                to={ROUTES.PLAYERS}
                className="text-gray-600 hover:text-gray-900"
              >
                Jugadores
              </Link>
              <Link
                to={ROUTES.MATCHUPS}
                className="text-gray-600 hover:text-gray-900"
              >
                Enfrentamientos
              </Link>
              <Link
                to={ROUTES.TOURNAMENTS}
                className="text-gray-600 hover:text-gray-900"
              >
                Torneos
              </Link>
            </div>
          </div>
        </div>
      </nav>
      <main className="container mx-auto px-4 py-8">{children}</main>
    </div>
  );
};

export default Layout;
