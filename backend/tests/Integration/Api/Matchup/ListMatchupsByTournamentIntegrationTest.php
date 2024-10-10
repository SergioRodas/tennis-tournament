<?php

namespace App\Tests\Integration\Api\Matchup;

use App\Tests\Integration\Shared\Infrastructure\IntegrationTestCase;
use App\Api\Matchup\Application\UseCases\ListMatchupsByTournamentUseCase;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\PlayerRepository;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Api\Player\Domain\Player;
use App\Api\Tournament\Domain\Tournament;
use App\Shared\Domain\Exception\ApiException;

class ListMatchupsByTournamentIntegrationTest extends IntegrationTestCase
{
    private ListMatchupsByTournamentUseCase $listMatchupsByTournamentUseCase;
    private MatchupRepository $matchupRepository;
    private PlayerRepository $playerRepository;
    private TournamentRepository $tournamentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->listMatchupsByTournamentUseCase = self::getContainer()->get(ListMatchupsByTournamentUseCase::class);
        $this->matchupRepository = self::getContainer()->get(MatchupRepository::class);
        $this->playerRepository = self::getContainer()->get(PlayerRepository::class);
        $this->tournamentRepository = self::getContainer()->get(TournamentRepository::class);
    }

    public function testListMatchupsByTournament(): void
    {
        $tournament = new Tournament('M');
        $this->tournamentRepository->save($tournament);

        $player1 = new Player('John Doe', 80, 'M', 70, 75);
        $player2 = new Player('Jane Doe', 85, 'F', null, null, 0.5);
        $player3 = new Player('Bob Smith', 75, 'M', 65, 70);

        $this->playerRepository->save($player1);
        $this->playerRepository->save($player2);
        $this->playerRepository->save($player3);

        $this->matchupRepository->save($player1->getId(), $player2->getId(), $tournament->getId());
        $this->matchupRepository->save($player1->getId(), $player3->getId(), $tournament->getId());

        $result = $this->listMatchupsByTournamentUseCase->execute(['tournament_id' => $tournament->getId()]);

        $this->assertArrayHasKey('tournament_id', $result);
        $this->assertArrayHasKey('matchups', $result);
        $this->assertCount(2, $result['matchups']);
    }

    public function testListMatchupsByNonExistingTournament(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('No matchups found for this tournament');

        $this->listMatchupsByTournamentUseCase->execute(['tournament_id' => 999]);
    }
}
