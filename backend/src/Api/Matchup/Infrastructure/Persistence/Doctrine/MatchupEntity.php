<?php

namespace App\Api\Matchup\Infrastructure\Persistence\Doctrine;

use App\Api\Player\Infrastructure\Persistence\Doctrine\PlayerEntity;
use App\Api\Tournament\Infrastructure\Persistence\Doctrine\TournamentEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'matchups')]
class MatchupEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: PlayerEntity::class)]
    #[ORM\JoinColumn(name: 'player1_id', referencedColumnName: 'id')]
    private PlayerEntity $player1;

    #[ORM\ManyToOne(targetEntity: PlayerEntity::class)]
    #[ORM\JoinColumn(name: 'player2_id', referencedColumnName: 'id')]
    private PlayerEntity $player2;

    #[ORM\ManyToOne(targetEntity: PlayerEntity::class)]
    #[ORM\JoinColumn(name: 'winner_id', referencedColumnName: 'id', nullable: true)]
    private ?PlayerEntity $winner = null;

    #[ORM\ManyToOne(targetEntity: TournamentEntity::class, inversedBy: 'matchups')]
    #[ORM\JoinColumn(name: 'tournament_id', referencedColumnName: 'id')]
    private ?TournamentEntity $tournament = null;

    public function __construct(PlayerEntity $player1, PlayerEntity $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    // Getters y setters

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlayer1(): PlayerEntity
    {
        return $this->player1;
    }

    public function setPlayer1(PlayerEntity $player1): void
    {
        $this->player1 = $player1;
    }

    public function getPlayer2(): PlayerEntity
    {
        return $this->player2;
    }

    public function setPlayer2(PlayerEntity $player2): void
    {
        $this->player2 = $player2;
    }

    public function getWinner(): ?PlayerEntity
    {
        return $this->winner;
    }

    public function setWinner(?PlayerEntity $winner): void
    {
        $this->winner = $winner;
    }

    public function getTournament(): ?TournamentEntity
    {
        return $this->tournament;
    }

    public function setTournament(TournamentEntity $tournament): void
    {
        $this->tournament = $tournament;
    }
}
