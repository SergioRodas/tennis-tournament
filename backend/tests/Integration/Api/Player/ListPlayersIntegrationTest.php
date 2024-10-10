<?php

namespace App\Tests\Integration\Api\Player;

use App\Tests\Integration\Shared\Infrastructure\IntegrationTestCase;
use App\Api\Player\Application\UseCases\ListPlayersUseCase;
use App\Api\Player\Domain\PlayerRepository;
use App\Api\Player\Domain\Player;

class ListPlayersIntegrationTest extends IntegrationTestCase
{
    private ListPlayersUseCase $listPlayersUseCase;
    private PlayerRepository $playerRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->listPlayersUseCase = self::getContainer()->get(ListPlayersUseCase::class);
        $this->playerRepository = self::getContainer()->get(PlayerRepository::class);
    }

    public function testListPlayersWithoutFilters(): void
    {
        $this->createSamplePlayers();

        $players = $this->listPlayersUseCase->execute([]);

        $this->assertCount(3, $players);
    }

    public function testListPlayersWithGenderFilter(): void
    {
        $this->createSamplePlayers();

        $players = $this->listPlayersUseCase->execute(['gender' => 'M']);

        $this->assertCount(2, $players);
        foreach ($players as $player) {
            $this->assertEquals('M', $player->getGender());
        }
    }

    public function testListPlayersWithSkillFilter(): void
    {
        $this->createSamplePlayers();

        $players = $this->listPlayersUseCase->execute(['skill' => 80]);

        $this->assertCount(1, $players);
        $this->assertEquals(80, $players[0]->getSkillLevel());
    }

    public function testListPlayersWithPagination(): void
    {
        $this->createSamplePlayers();

        $players = $this->listPlayersUseCase->execute(['page' => 1, 'limit' => 2]);

        $this->assertCount(2, $players);
    }

    private function createSamplePlayers(): void
    {
        $players = [
            new Player('Player 1', 70, 'M'),
            new Player('Player 2', 80, 'F'),
            new Player('Player 3', 90, 'M'),
        ];

        foreach ($players as $player) {
            $this->playerRepository->save($player);
        }
    }
}
