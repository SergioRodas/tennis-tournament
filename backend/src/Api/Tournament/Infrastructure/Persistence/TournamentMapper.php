<?php

namespace App\Api\Tournament\Infrastructure\Persistence;

use App\Api\Player\Infrastructure\Persistence\PlayerMapper;
use App\Api\Tournament\Domain\Tournament;
use App\Api\Tournament\Infrastructure\Persistence\Doctrine\TournamentEntity;

class TournamentMapper
{
    public static function toEntity(Tournament $tournament): TournamentEntity
    {
        $tournamentEntity = new TournamentEntity($tournament->getGender());

        if ($tournament->getWinner()) {
            $tournamentEntity->setWinner(PlayerMapper::toEntity($tournament->getWinner()));
        }

        return $tournamentEntity;
    }

    public static function toDomain(TournamentEntity $tournamentEntity): Tournament
    {
        $tournament = new Tournament($tournamentEntity->getGender());

        $tournament->setCreatedAt($tournamentEntity->getCreatedAt());
        $tournament->setId($tournamentEntity->getId());

        if ($tournamentEntity->getWinner()) {
            $tournamentWinner = PlayerMapper::toDomain($tournamentEntity->getWinner());
            $tournament->setWinner($tournamentWinner);
        }

        if ($tournamentEntity->getFinishedAt()) {
            $tournament->setFinishedAt($tournamentEntity->getFinishedAt());
        }

        return $tournament;
    }
}
