<?php

namespace App\Api\Tournament\Domain;

use App\Api\Player\Domain\Player;

interface TournamentRepository
{
    public function save(Tournament $tournament): void;

    public function findById(int $id): ?Tournament;

    public function setWinner(int $tournamentId, Player $winner): void;

    public function findByFilters(array $filters, int $offset = 0, int $limit = 10): array;
}
