<?php

namespace App\Tests\Integration\Api\Tournament;

use App\Tests\Integration\Shared\Infrastructure\IntegrationTestCase;
use App\Api\Tournament\Application\UseCases\SimulateTournamentUseCase;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\PlayerRepository;
use App\Api\Tournament\Domain\Tournament;
use App\Api\Player\Domain\Player;
use App\Shared\Domain\Exception\ApiException;

class SimulateTournamentIntegrationTest extends IntegrationTestCase
{
    private SimulateTournamentUseCase $simulateTournamentUseCase;
    private TournamentRepository $tournamentRepository;
    private MatchupRepository $matchupRepository;
    private PlayerRepository $playerRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->simulateTournamentUseCase = self::getContainer()->get(SimulateTournamentUseCase::class);
        $this->tournamentRepository = self::getContainer()->get(TournamentRepository::class);
        $this->matchupRepository = self::getContainer()->get(MatchupRepository::class);
        $this->playerRepository = self::getContainer()->get(PlayerRepository::class);
    }

    public function testSimulateTournament(): void
    {
        $tournament = new Tournament('M');
        $this->tournamentRepository->save($tournament);

        $players = $this->createPlayers(4, 'M');
        $this->createMatchups($tournament, $players);

        $winner = $this->simulateTournamentUseCase->execute($tournament->getId());

        $this->assertInstanceOf(Player::class, $winner);
        $this->assertEquals('M', $winner->getGender());

        $updatedTournament = $this->tournamentRepository->findById($tournament->getId());
        $this->assertEquals($winner->getId(), $updatedTournament->getWinner()->getId());
    }

    public function testSimulateTournamentWithInvalidNumberOfMatchups(): void
    {
        $tournament = new Tournament('M');
        $this->tournamentRepository->save($tournament);

        $players = $this->createPlayers(3, 'M');
        $this->createMatchups($tournament, $players);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Invalid number of matchups');

        $this->simulateTournamentUseCase->execute($tournament->getId());
    }

    private function createPlayers(int $count, string $gender): array
    {
        $players = [];
        for ($i = 0; $i < $count; ++$i) {
            $player = new Player("Player $i", 80, $gender, 70, 75);
            $this->playerRepository->save($player);
            $players[] = $player;
        }

        return $players;
    }

    private function createMatchups(Tournament $tournament, array $players): void
    {
        for ($i = 0; $i < count($players); $i += 2) {
            if (isset($players[$i + 1])) {
                $this->matchupRepository->save($players[$i]->getId(), $players[$i + 1]->getId(), $tournament->getId());
            }
        }
    }
}
