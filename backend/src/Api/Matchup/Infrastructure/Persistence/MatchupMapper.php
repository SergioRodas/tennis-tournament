<?php

namespace App\Api\Matchup\Infrastructure\Persistence;

use App\Api\Matchup\Domain\Matchup;
use App\Api\Matchup\Infrastructure\Persistence\Doctrine\MatchupEntity;
use App\Api\Player\Infrastructure\Persistence\PlayerMapper;
use App\Api\Tournament\Infrastructure\Persistence\TournamentMapper;

class MatchupMapper
{
    public static function toDomain(MatchupEntity $matchupEntity): Matchup
    {
        // Convertir entidades a objetos del dominio
        $player1 = PlayerMapper::toDomain($matchupEntity->getPlayer1());
        $player2 = PlayerMapper::toDomain($matchupEntity->getPlayer2());
        $tournament = TournamentMapper::toDomain($matchupEntity->getTournament());

        // Crear un nuevo Matchup desde el dominio
        $matchup = new Matchup($player1, $player2, $tournament);
        $matchup->setId($matchupEntity->getId());

        // Setear el ganador si existe
        if ($matchupEntity->getWinner()) {
            $winner = PlayerMapper::toDomain($matchupEntity->getWinner());
            $matchup->setWinner($winner);
        }

        return $matchup;
    }
}
