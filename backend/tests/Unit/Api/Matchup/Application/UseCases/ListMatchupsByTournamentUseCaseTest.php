<?php

namespace Tests\Unit\Api\Matchup\Application\UseCases;

use App\Api\Matchup\Application\UseCases\ListMatchupsByTournamentUseCase;
use App\Api\Matchup\Domain\Matchup;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\Player;
use App\Api\Tournament\Domain\Tournament;
use App\Shared\Domain\Exception\ApiException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ListMatchupsByTournamentUseCaseTest extends TestCase
{
    /** @var MatchupRepository&MockObject */
    private $matchupRepository;
    private ListMatchupsByTournamentUseCase $useCase;

    protected function setUp(): void
    {
        $this->matchupRepository = $this->createMock(MatchupRepository::class);
        $this->useCase = new ListMatchupsByTournamentUseCase($this->matchupRepository);
    }

    public function testListMatchupsSuccess()
    {
        $tournamentId = 1;
        $player1 = new Player('John Doe', 80, 'M', 70, 75);
        $player2 = new Player('Jane Doe', 85, 'F', null, null, 0.5);
        /** @var Tournament&MockObject */
        $tournament = $this->createMock(Tournament::class);
        $matchup1 = new Matchup($player1, $player2, $tournament);
        $matchup2 = new Matchup($player2, $player1, $tournament);

        $this->matchupRepository->expects($this->once())
            ->method('findByTournamentId')
            ->with($tournamentId, null)
            ->willReturn([$matchup1, $matchup2]);

        $result = $this->useCase->execute(['tournament_id' => $tournamentId]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('tournament_id', $result);
        $this->assertArrayHasKey('matchups', $result);
        $this->assertCount(2, $result['matchups']);
        $this->assertEquals($tournamentId, $result['tournament_id']);
    }

    public function testListMatchupsWithFinishedFilter()
    {
        $tournamentId = 1;
        $finished = 'true';
        $player1 = new Player('John Doe', 80, 'M', 70, 75);
        $player2 = new Player('Jane Doe', 85, 'F', null, null, 0.5);
        /** @var Tournament&MockObject */
        $tournament = $this->createMock(Tournament::class);
        $matchup = new Matchup($player1, $player2, $tournament);

        $this->matchupRepository->expects($this->once())
            ->method('findByTournamentId')
            ->with($tournamentId, true)
            ->willReturn([$matchup]);

        $result = $this->useCase->execute(['tournament_id' => $tournamentId, 'finished' => $finished]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('tournament_id', $result);
        $this->assertArrayHasKey('matchups', $result);
        $this->assertCount(1, $result['matchups']);
    }

    public function testListMatchupsNoTournamentId()
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Tournament ID is required');

        $this->useCase->execute([]);
    }

    public function testListMatchupsNoResults()
    {
        $tournamentId = 999;

        $this->matchupRepository->expects($this->once())
            ->method('findByTournamentId')
            ->with($tournamentId, null)
            ->willReturn([]);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('No matchups found for this tournament');

        $this->useCase->execute(['tournament_id' => $tournamentId]);
    }
}
