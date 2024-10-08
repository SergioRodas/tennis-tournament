<?php

namespace App\Api\Matchup\Domain;

interface MatchupRepository
{
    public function save(int $player1Id, int $player2Id, int $tournamentId): void;

    public function findById(int $id): ?Matchup;

    public function findAll(): array;

    public function findByTournamentId(int $tournamentId, ?bool $finished = null): array;

    public function updateWinner(int $matchupId, int $winnerId): void;
}
