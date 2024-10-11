import React from 'react';
import { Link } from 'react-router-dom';

import { ROUTES } from '../config/urls';

import { Button } from '@/components/ui/button';
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
} from '@/components/ui/card';

const HomePage: React.FC = () => {
  return (
    <div className="container mx-auto p-4 min-h-screen">
      <h1 className="text-4xl font-bold my-4 text-center">
        Torneo de Tenis Élite
      </h1>

      <div className="mb-8 max-w-2xl mx-auto text-center">
        <p className="text-sm text-gray-700">
          Prepárate para vivir la emoción de un torneo de tenis único, donde la
          habilidad, la estrategia y un toque de suerte se combinan para coronar
          al campeón definitivo.
        </p>
      </div>

      <div className="max-w-3xl mx-auto text-sm mb-12 bg-white p-6 rounded-lg shadow-md">
        <h2 className="text-xl font-bold mb-4 text-center text-blue-500">
          Reglas del Torneo
        </h2>
        <ul className="list-disc list-inside mb-6 space-y-2 text-gray-700">
          <li>
            Eliminación directa: Cada partido es decisivo. El ganador avanza, el
            perdedor se despide del torneo.
          </li>
          <li>
            Categorías: Elige entre torneos Masculinos o Femeninos, cada uno con
            sus propias dinámicas.
          </li>
          <li>
            Habilidad y Suerte: Cada jugador tiene un nivel de habilidad, pero
            la suerte también juega su papel en cada encuentro.
          </li>
          <li>Sin empates: Siempre habrá un ganador en cada partido.</li>
        </ul>

        <h2 className="text-xl font-bold mb-4 text-center text-blue-500">
          Factores Decisivos
        </h2>
        <p className="mb-6 text-center text-gray-700">
          En el torneo masculino, la fuerza y la velocidad son cruciales. Para
          las mujeres, el tiempo de reacción marca la diferencia. Estos
          factores, combinados con la habilidad base y ese toque de fortuna,
          determinarán quién avanza en cada ronda.
        </p>

        <p className="font-semibold text-center text-base text-blue-400">
          ¿Tienes lo necesario para llegar a la cima? ¡Que comience el torneo!
        </p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <Card className="bg-gradient-to-br from-blue-500 to-blue-600 text-white">
          <CardHeader>
            <CardTitle>Jugadores</CardTitle>
            <CardDescription className="text-blue-100">
              Gestiona los jugadores del torneo
            </CardDescription>
          </CardHeader>
          <div className="p-4">
            <Button
              asChild
              className="w-full bg-white text-blue-600 hover:bg-blue-100"
            >
              <Link to={ROUTES.PLAYERS}>Ver Jugadores</Link>
            </Button>
          </div>
        </Card>
        <Card className="bg-gradient-to-br from-green-500 to-green-600 text-white">
          <CardHeader>
            <CardTitle>Enfrentamientos</CardTitle>
            <CardDescription className="text-green-100">
              Visualiza los enfrentamientos del torneo
            </CardDescription>
          </CardHeader>
          <div className="p-4">
            <Button
              asChild
              className="w-full bg-white text-green-600 hover:bg-green-100"
            >
              <Link to={ROUTES.MATCHUPS}>Ver Enfrentamientos</Link>
            </Button>
          </div>
        </Card>
        <Card className="bg-gradient-to-br from-purple-500 to-purple-600 text-white">
          <CardHeader>
            <CardTitle>Torneos</CardTitle>
            <CardDescription className="text-purple-100">
              Administra los torneos
            </CardDescription>
          </CardHeader>
          <div className="p-4">
            <Button
              asChild
              className="w-full bg-white text-purple-600 hover:bg-purple-100"
            >
              <Link to={ROUTES.TOURNAMENTS}>Ver Torneos</Link>
            </Button>
          </div>
        </Card>
      </div>
    </div>
  );
};

export default HomePage;
