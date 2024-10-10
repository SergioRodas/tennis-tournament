<?php

namespace App\Tests\Integration\Api\Player;

use App\Tests\Integration\Shared\Infrastructure\IntegrationTestCase;
use App\Api\Player\Application\UseCases\CreatePlayerUseCase;
use App\Api\Player\Domain\PlayerRepository;
use App\Api\Player\Domain\Player;
use App\Shared\Domain\Exception\ApiException;

class CreatePlayerIntegrationTest extends IntegrationTestCase
{
    private CreatePlayerUseCase $createPlayerUseCase;
    private PlayerRepository $playerRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createPlayerUseCase = self::getContainer()->get(CreatePlayerUseCase::class);
        $this->playerRepository = self::getContainer()->get(PlayerRepository::class);
    }

    public function testCreateMalePlayer(): void
    {
        $playerData = [
            'name' => 'John Doe',
            'skillLevel' => 75,
            'gender' => 'M',
            'strength' => 80,
            'speed' => 70,
        ];

        $player = $this->createPlayerUseCase->execute($playerData);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertNotNull($player->getId());
        $this->assertEquals('John Doe', $player->getName());
        $this->assertEquals(75, $player->getSkillLevel());
        $this->assertEquals('M', $player->getGender());
        $this->assertEquals(80, $player->getStrength());
        $this->assertEquals(70, $player->getSpeed());
        $this->assertNull($player->getReactionTime());

        $savedPlayer = $this->playerRepository->findById($player->getId());
        $this->assertNotNull($savedPlayer);
        $this->assertEquals($player->getName(), $savedPlayer->getName());
    }

    public function testCreateFemalePlayer(): void
    {
        $playerData = [
            'name' => 'Jane Doe',
            'skillLevel' => 85,
            'gender' => 'F',
            'reactionTime' => 0.5,
        ];

        $player = $this->createPlayerUseCase->execute($playerData);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertNotNull($player->getId());
        $this->assertEquals('Jane Doe', $player->getName());
        $this->assertEquals(85, $player->getSkillLevel());
        $this->assertEquals('F', $player->getGender());
        $this->assertNull($player->getStrength());
        $this->assertNull($player->getSpeed());
        $this->assertEquals(0.5, $player->getReactionTime());

        $savedPlayer = $this->playerRepository->findById($player->getId());
        $this->assertNotNull($savedPlayer);
        $this->assertEquals($player->getName(), $savedPlayer->getName());
    }

    public function testCreatePlayerWithInvalidData(): void
    {
        $playerData = [
            'name' => 'Invalid Player',
            'skillLevel' => 150, // Invalid skill level
            'gender' => 'X', // Invalid gender
        ];

        $this->expectException(ApiException::class);
        $this->createPlayerUseCase->execute($playerData);
    }
}
