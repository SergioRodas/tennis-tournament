<?php

namespace App\Api\Player\Infrastructure\Persistence;

use App\Api\Player\Domain\Player;
use App\Api\Player\Infrastructure\Persistence\Doctrine\PlayerEntity;

class PlayerMapper
{
    public static function toEntity(Player $player): PlayerEntity
    {
        return new PlayerEntity($player);
    }

    public static function toDomain(PlayerEntity $playerEntity): Player
    {
        $player = new Player(
            $playerEntity->getName(),
            $playerEntity->getSkillLevel(),
            $playerEntity->getGender(),
            $playerEntity->getStrength(),
            $playerEntity->getSpeed(),
            $playerEntity->getReactionTime()
        );

        $player->setId($playerEntity->getId());

        return $player;
    }
}
