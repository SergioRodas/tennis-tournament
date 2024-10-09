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
        $this->name = $name;
        $this->skillLevel = $skillLevel;
        $this->gender = $gender;

        // Validar los atributos adicionales según el género
        if ('M' === $gender) {
            $this->strength = $strength;
            $this->speed = $speed;
        } elseif ('F' === $gender) {
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
