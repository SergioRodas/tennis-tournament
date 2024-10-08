<?php

namespace App\Api\Player\Infrastructure\Persistence\Doctrine;

use App\Api\Player\Domain\Player;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'players')]
class PlayerEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $skillLevel;

    #[ORM\Column(type: 'string', length: 1)]
    private string $gender;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $strength;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $speed;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $reactionTime;

    public function __construct(Player $player)
    {
        $this->name = $player->getName();
        $this->skillLevel = $player->getSkillLevel();
        $this->gender = $player->getGender();
        $this->strength = $player->getStrength();
        $this->speed = $player->getSpeed();
        $this->reactionTime = $player->getReactionTime();
    }

    // Getters y setters...

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSkillLevel(): int
    {
        return $this->skillLevel;
    }

    public function setSkillLevel(int $skillLevel): void
    {
        $this->skillLevel = $skillLevel;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(?int $strength): void
    {
        $this->strength = $strength;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(?int $speed): void
    {
        $this->speed = $speed;
    }

    public function getReactionTime(): ?int
    {
        return $this->reactionTime;
    }

    public function setReactionTime(?int $reactionTime): void
    {
        $this->reactionTime = $reactionTime;
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
