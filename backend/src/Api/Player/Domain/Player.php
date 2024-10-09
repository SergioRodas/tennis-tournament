<?php

namespace App\Api\Player\Domain;

class Player
{
    private int $id;

    private string $name;
    private int $skillLevel; // Nivel de habilidad entre 0 y 100
    private ?int $strength = null; // Solo para masculino
    private ?int $speed = null; // Solo para masculino
    private ?float $reactionTime = null; // Solo para femenino, ahora es float
    private string $gender; // 'M' o 'F'

    public function __construct(string $name, int $skillLevel, string $gender, ?int $strength = null, ?int $speed = null, ?float $reactionTime = null)
    {
        if (!in_array($gender, ['M', 'F'])) {
            throw new \InvalidArgumentException('Gender must be either M or F');
        }

        if ($skillLevel < 0 || $skillLevel > 100) {
            throw new \InvalidArgumentException('Skill level must be between 0 and 100');
        }

        $this->name = $name;
        $this->skillLevel = $skillLevel;
        $this->gender = $gender;

        if ('M' === $gender) {
            if ($strength !== null && ($strength < 0 || $strength > 100)) {
                throw new \InvalidArgumentException('Strength must be between 0 and 100');
            }
            if ($speed !== null && ($speed < 0 || $speed > 100)) {
                throw new \InvalidArgumentException('Speed must be between 0 and 100');
            }
            $this->strength = $strength;
            $this->speed = $speed;
        } elseif ('F' === $gender) {
            if ($reactionTime !== null && ($reactionTime < 0 || $reactionTime > 100)) {
                throw new \InvalidArgumentException('Reaction time must be between 0 and 100');
            }
            $this->reactionTime = $reactionTime;
        }
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSkillLevel(): int
    {
        return $this->skillLevel;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function getReactionTime(): ?float
    {
        return $this->reactionTime;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'skillLevel' => $this->skillLevel,
            'gender' => $this->gender,
            'strength' => $this->strength,
            'speed' => $this->speed,
            'reactionTime' => $this->reactionTime,
        ];
    }
}
