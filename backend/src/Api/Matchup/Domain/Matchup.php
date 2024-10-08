<?php

namespace App\Api\Matchup\Domain;

use App\Api\Player\Domain\Player;
use App\Api\Tournament\Domain\Tournament;

class Matchup
{
    private int $id;
    private Player $player1;
    private Player $player2;
    private ?Player $winner = null;
    private Tournament $tournament;

    public function __construct(Player $player1, Player $player2, Tournament $tournament)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->tournament = $tournament;
    }

    // Getters y Setters

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setWinner(Player $winner): void
    {
        $this->winner = $winner;
    }

    public function getPlayer1(): Player
    {
        return $this->player1;
    }

    public function getPlayer2(): Player
    {
        return $this->player2;
    }

    public function getWinner(): ?Player
    {
        return $this->winner;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function toArray(): array
    {
        return [
            'matchup_id' => $this->id,
            'winner_id' => $this->winner ? $this->winner->getId() : null,
            'player1' => $this->player1->toArray(),
            'player2' => $this->player2->toArray(),
        ];
    }
}
