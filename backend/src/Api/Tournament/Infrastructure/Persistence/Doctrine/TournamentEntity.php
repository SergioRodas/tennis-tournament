<?php

namespace App\Api\Tournament\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use App\Api\Player\Infrastructure\Persistence\Doctrine\PlayerEntity;
use App\Api\Matchup\Infrastructure\Persistence\Doctrine\MatchupEntity;

#[ORM\Entity]
#[ORM\Table(name: 'tournaments')]
class TournamentEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToMany(targetEntity: PlayerEntity::class)]

    #[ORM\ManyToOne(targetEntity: PlayerEntity::class)]
    #[ORM\JoinColumn(name: 'winner_id', referencedColumnName: 'id', nullable: true)]
    private ?PlayerEntity $winner = null;

    #[ORM\Column(type: 'string')]
    private string $gender; // Género del torneo

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt; // Fecha de creación

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $finishedAt = null; // Fecha de finalización

    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: MatchupEntity::class, cascade: ['persist', 'remove'])]
    public function __construct(string $gender)
    {
        $this->gender = $gender;
        $this->createdAt = new \DateTime(); // Fecha de creación se establece al momento de la creación
    }

    // Getters y Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function setWinner(?PlayerEntity $winner): void
    {
        $this->winner = $winner;
        $this->finishedAt = new \DateTime(); // Fecha de finalización se establece al declarar un ganador
    }

    public function getWinner(): ?PlayerEntity
    {
        return $this->winner;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getFinishedAt(): ?\DateTime
    {
        return $this->finishedAt;
    }
}
