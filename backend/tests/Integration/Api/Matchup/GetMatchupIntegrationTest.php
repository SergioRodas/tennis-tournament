<?php

namespace App\Tests\Integration\Api\Matchup;

use App\Tests\Integration\Shared\Infrastructure\IntegrationTestCase;
use App\Api\Matchup\Application\UseCases\GetMatchupUseCase;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\PlayerRepository;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Api\Player\Domain\Player;
use App\Api\Tournament\Domain\Tournament;
use App\Api\Matchup\Domain\Matchup;
use App\Shared\Domain\Exception\ApiException;

class GetMatchupIntegrationTest extends IntegrationTestCase
{
    private GetMatchupUseCase $getMatchupUseCase;
    private MatchupRepository $matchupRepository;
    private PlayerRepository $playerRepository;
    private TournamentRepository $tournamentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getMatchupUseCase = self::getContainer()->get(GetMatchupUseCase::class);
        $this->matchupRepository = self::getContainer()->get(MatchupRepository::class);
        $this->playerRepository = self::getContainer()->get(PlayerRepository::class);
        $this->tournamentRepository = self::getContainer()->get(TournamentRepository::class);
    }

    public function testGetExistingMatchup(): void
    {
        $tournament = new Tournament('Test Tournament', 'M');
        $this->tournamentRepository->save($tournament);

        $player1 = new Player('John Doe', 80, 'M', 70, 75);
        $player2 = new Player('Jane Doe', 80, 'M', 70, 75);
        $this->playerRepository->save($player1);
        $this->playerRepository->save($player2);

        $this->matchupRepository->save($player1->getId(), $player2->getId(), $tournament->getId());

        $matchups = $this->matchupRepository->findByTournamentId($tournament->getId());
        $this->assertCount(1, $matchups);
        $matchup = $matchups[0];

        $retrievedMatchup = $this->getMatchupUseCase->execute($matchup->getId());

        $this->assertInstanceOf(Matchup::class, $retrievedMatchup);
        $this->assertEquals($player1->getId(), $retrievedMatchup->getPlayer1()->getId());
        $this->assertEquals($player2->getId(), $retrievedMatchup->getPlayer2()->getId());
        $this->assertEquals($tournament->getId(), $retrievedMatchup->getTournament()->getId());
    }

    public function testGetNonExistingMatchup(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Matchup not found');

        $this->getMatchupUseCase->execute(999);
    }
}
