<?php

namespace App\Api\Tournament\Domain;

use App\Api\Player\Domain\Player;

class Tournament
{
    private int $id;
    private ?Player $winner = null;
    private string $gender; // Género del torneo (masculino o femenino)
    private \DateTime $createdAt; // Fecha de creación
    private ?\DateTime $finishedAt = null; // Fecha de finalización

    public function __construct(string $gender)
    {
        $this->gender = $gender;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setWinner(Player $winner): void
    {
        $this->winner = $winner;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setFinishedAt(\DateTime $finishedAt): void
    {
        $this->finishedAt = $finishedAt;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getWinner(): ?Player
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

    // Valida si los jugadores cumplen con las reglas del torneo
    public function canPlayersParticipate(Player $player1, Player $player2): bool
    {
        return $player1->getGender() === $this->getGender() && $player2->getGender() === $this->getGender();
    }

    // Método toArray
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'gender' => $this->gender,
            'winner' => $this->winner ? $this->winner->toArray() : null,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'finished_at' => $this->finishedAt ? $this->finishedAt->format('Y-m-d H:i:s') : null,
        ];
    }
}
