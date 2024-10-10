<?php

namespace App\Tests\Integration\Api\Player;

use App\Tests\Integration\Shared\Infrastructure\IntegrationTestCase;
use App\Api\Player\Application\UseCases\GetPlayerUseCase;
use App\Api\Player\Domain\PlayerRepository;
use App\Api\Player\Domain\Player;
use App\Shared\Domain\Exception\ApiException;

class GetPlayerIntegrationTest extends IntegrationTestCase
{
    private GetPlayerUseCase $getPlayerUseCase;
    private PlayerRepository $playerRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getPlayerUseCase = self::getContainer()->get(GetPlayerUseCase::class);
        $this->playerRepository = self::getContainer()->get(PlayerRepository::class);
    }

    public function testGetExistingPlayer(): void
    {
        $player = new Player('Test Player', 50, 'M');
        $this->playerRepository->save($player);

        $retrievedPlayer = $this->getPlayerUseCase->execute($player->getId());

        $this->assertInstanceOf(Player::class, $retrievedPlayer);
        $this->assertEquals($player->getId(), $retrievedPlayer->getId());
        $this->assertEquals($player->getName(), $retrievedPlayer->getName());
    }

    public function testGetNonExistingPlayer(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Player not found');

        $this->getPlayerUseCase->execute(9999);
    }
}
