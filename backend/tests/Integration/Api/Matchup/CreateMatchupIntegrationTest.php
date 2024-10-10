<?php

namespace App\Tests\Integration\Api\Matchup;

use App\Tests\Integration\Shared\Infrastructure\IntegrationTestCase;
use App\Api\Matchup\Application\UseCases\CreateMatchupUseCase;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\PlayerRepository;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Api\Player\Domain\Player;
use App\Api\Tournament\Domain\Tournament;
use App\Shared\Domain\Exception\ApiException;

class CreateMatchupIntegrationTest extends IntegrationTestCase
{
    private CreateMatchupUseCase $createMatchupUseCase;
    private MatchupRepository $matchupRepository;
    private PlayerRepository $playerRepository;
    private TournamentRepository $tournamentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createMatchupUseCase = self::getContainer()->get(CreateMatchupUseCase::class);
        $this->matchupRepository = self::getContainer()->get(MatchupRepository::class);
        $this->playerRepository = self::getContainer()->get(PlayerRepository::class);
        $this->tournamentRepository = self::getContainer()->get(TournamentRepository::class);
    }

    public function testCreateMatchup(): void
    {
        $tournament = new Tournament('M');
        $this->tournamentRepository->save($tournament);

        $player1 = new Player('John Doe', 80, 'M', 70, 75);
        $player2 = new Player('Jane Doe', 80, 'M', 70, 75);
        $this->playerRepository->save($player1);
        $this->playerRepository->save($player2);

        $matchupData = [
            'player1_id' => $player1->getId(),
            'player2_id' => $player2->getId(),
            'tournament_id' => $tournament->getId(),
        ];

        $this->createMatchupUseCase->execute($matchupData);

        $matchups = $this->matchupRepository->findByTournamentId($tournament->getId());
        $this->assertCount(1, $matchups);
        $this->assertEquals($player1->getId(), $matchups[0]->getPlayer1()->getId());
        $this->assertEquals($player2->getId(), $matchups[0]->getPlayer2()->getId());
    }

    public function testCreateMatchupWithInvalidPlayers(): void
    {
        $tournament = new Tournament('M');
        $this->tournamentRepository->save($tournament);

        $matchupData = [
            'player1_id' => 999,
            'player2_id' => 1000,
            'tournament_id' => $tournament->getId(),
        ];

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Invalid players or tournament');

        $this->createMatchupUseCase->execute($matchupData);
    }
}
