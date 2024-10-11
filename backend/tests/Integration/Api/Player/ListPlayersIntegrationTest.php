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

        $result = $this->listPlayersUseCase->execute([]);

        $this->assertArrayHasKey('players', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertCount(3, $result['players']);
    }

    public function testListPlayersWithGenderFilter(): void
    {
        $this->createSamplePlayers();

        $result = $this->listPlayersUseCase->execute(['gender' => 'M']);

        $this->assertCount(2, $result['players']);
        foreach ($result['players'] as $player) {
            $this->assertEquals('M', $player->getGender());
        }
    }

    public function testListPlayersWithSkillFilter(): void
    {
        $this->createSamplePlayers();

        $result = $this->listPlayersUseCase->execute(['skill' => 80]);

        $this->assertCount(1, $result['players']);
        $this->assertEquals(80, $result['players'][0]->getSkillLevel());
    }

    public function testListPlayersWithPagination(): void
    {
        $this->createSamplePlayers();

        $result = $this->listPlayersUseCase->execute(['page' => 1, 'limit' => 2]);

        $this->assertCount(2, $result['players']);
        $this->assertEquals(2, $result['pagination']['itemsPerPage']);
        $this->assertEquals(1, $result['pagination']['currentPage']);
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
