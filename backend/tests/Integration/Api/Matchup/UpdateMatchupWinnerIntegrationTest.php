<?php

namespace App\Tests\Integration\Api\Matchup;

use App\Tests\Integration\Shared\Infrastructure\IntegrationTestCase;
use App\Api\Matchup\Application\UseCases\UpdateMatchupWinnerUseCase;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\PlayerRepository;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Api\Player\Domain\Player;
use App\Api\Tournament\Domain\Tournament;
use App\Shared\Domain\Exception\ApiException;

class UpdateMatchupWinnerIntegrationTest extends IntegrationTestCase
{
    private UpdateMatchupWinnerUseCase $updateMatchupWinnerUseCase;
    private MatchupRepository $matchupRepository;
    private PlayerRepository $playerRepository;
    private TournamentRepository $tournamentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->updateMatchupWinnerUseCase = self::getContainer()->get(UpdateMatchupWinnerUseCase::class);
        $this->matchupRepository = self::getContainer()->get(MatchupRepository::class);
        $this->playerRepository = self::getContainer()->get(PlayerRepository::class);
        $this->tournamentRepository = self::getContainer()->get(TournamentRepository::class);
    }

    public function testUpdateMatchupWinner(): void
    {
        $player1 = new Player('John Doe', 80, 'M', 70, 75);
        $player2 = new Player('Jane Doe', 85, 'F', null, null, 0.5);
        $tournament = new Tournament('M');

        $this->playerRepository->save($player1);
        $this->playerRepository->save($player2);
        $this->tournamentRepository->save($tournament);

        $this->matchupRepository->save($player1->getId(), $player2->getId(), $tournament->getId());
        $matchups = $this->matchupRepository->findByTournamentId($tournament->getId());
        $matchup = $matchups[0];

        $this->updateMatchupWinnerUseCase->execute($matchup->getId(), $player1->getId());

        $updatedMatchup = $this->matchupRepository->findById($matchup->getId());
        $this->assertEquals($player1->getId(), $updatedMatchup->getWinner()->getId());
    }

    public function testUpdateMatchupWinnerWithInvalidMatchup(): void
    {
        $player = new Player('John Doe', 80, 'M', 70, 75);
        $this->playerRepository->save($player);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Matchup not found');

        $this->updateMatchupWinnerUseCase->execute(999, $player->getId());
    }

    public function testUpdateMatchupWinnerWithInvalidPlayer(): void
    {
        $player1 = new Player('John Doe', 80, 'M', 70, 75);
        $player2 = new Player('Jane Doe', 85, 'F', null, null, 0.5);
        $tournament = new Tournament('M');

        $this->playerRepository->save($player1);
        $this->playerRepository->save($player2);
        $this->tournamentRepository->save($tournament);

        $this->matchupRepository->save($player1->getId(), $player2->getId(), $tournament->getId());
        $matchups = $this->matchupRepository->findByTournamentId($tournament->getId());
        $matchup = $matchups[0];

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Winner not found');

        $this->updateMatchupWinnerUseCase->execute($matchup->getId(), 999);
    }
}
