<?php

namespace App\Api\Tournament\Domain;

use App\Api\Player\Domain\Player;
use App\Shared\Domain\Exception\ApiException;

class Tournament
{
    private int $id;
    private string $name;
    private ?Player $winner = null;
    private string $gender; // Género del torneo (masculino o femenino)
    private \DateTime $createdAt; // Fecha de creación
    private ?\DateTime $finishedAt = null; // Fecha de finalización

    public function __construct(string $name, string $gender)
    {
        $this->name = $name;
        $this->setGender($gender);
        $this->createdAt = new \DateTime();
    }

    private function setGender(string $gender): void
    {
        if (!in_array($gender, ['M', 'F'])) {
            throw new ApiException('Invalid gender. Allowed values are \'M\' or \'F\'.', 400);
        }
        $this->gender = $gender;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
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
            'name' => $this->name,
            'gender' => $this->gender,
            'winner' => $this->winner ? $this->winner->toArray() : null,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'finished_at' => $this->finishedAt ? $this->finishedAt->format('Y-m-d H:i:s') : null,
        ];
    }
}
