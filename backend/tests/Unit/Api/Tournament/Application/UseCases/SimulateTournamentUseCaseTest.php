<?php

namespace Tests\Unit\Api\Tournament\Application\UseCases;

use App\Api\Matchup\Domain\Matchup;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\Player;
use App\Api\Tournament\Application\UseCases\SimulateTournamentUseCase;
use App\Api\Tournament\Domain\Tournament;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class SimulateTournamentUseCaseTest extends TestCase
{
    /** @var MatchupRepository&MockObject */
    private $matchupRepository;

    /** @var TournamentRepository&MockObject */
    private $tournamentRepository;

    private SimulateTournamentUseCase $useCase;

    protected function setUp(): void
    {
        $this->matchupRepository = $this->createMock(MatchupRepository::class);
        $this->tournamentRepository = $this->createMock(TournamentRepository::class);
        $this->useCase = new SimulateTournamentUseCase($this->matchupRepository, $this->tournamentRepository);
    }

    public function testSimulateTournamentSuccess()
    {
        $tournamentId = 1;
        $tournament = new Tournament('Test Tournament', 'M');
        $tournament->setId($tournamentId);

        $player1 = new Player('Player 1', 80, 'M', 70, 75);
        $player1->setId(1);
        $player2 = new Player('Player 2', 75, 'M', 65, 80);
        $player2->setId(2);
        $player3 = new Player('Player 3', 85, 'M', 75, 70);
        $player3->setId(3);
        $player4 = new Player('Player 4', 70, 'M', 60, 85);
        $player4->setId(4);

        $matchup1 = new Matchup($player1, $player2, $tournament);
        $matchup1->setId(1);
        $matchup2 = new Matchup($player3, $player4, $tournament);
        $matchup2->setId(2);

        $this->tournamentRepository->expects($this->once())
            ->method('findById')
            ->with($tournamentId)
            ->willReturn($tournament);

        $this->matchupRepository->expects($this->exactly(2))
            ->method('findByTournamentId')
            ->with($tournamentId, false)
            ->willReturnOnConsecutiveCalls([$matchup1, $matchup2], []);

        $this->matchupRepository->expects($this->exactly(2))
            ->method('updateWinner');

        $this->matchupRepository->expects($this->once())
            ->method('save');

        $this->tournamentRepository->expects($this->once())
            ->method('setWinner');

        $winner = $this->useCase->execute($tournamentId);

        $this->assertInstanceOf(Player::class, $winner);
    }

    public function testSimulateTournamentNotFound()
    {
        $tournamentId = 999;

        $this->tournamentRepository->expects($this->once())
            ->method('findById')
            ->with($tournamentId)
            ->willReturn(null);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Tournament not found');

        $this->useCase->execute($tournamentId);
    }

    public function testSimulateTournamentAlreadyFinished()
    {
        $tournamentId = 1;
        $tournament = new Tournament('Finished Tournament', 'M');
        $tournament->setId($tournamentId);
        $tournament->setWinner(new Player('Winner', 80, 'M', 70, 75));

        $this->tournamentRepository->expects($this->once())
            ->method('findById')
            ->with($tournamentId)
            ->willReturn($tournament);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Tournament has already finished');

        $this->useCase->execute($tournamentId);
    }

    public function testSimulateTournamentInvalidNumberOfMatchups()
    {
        $tournamentId = 1;
        $tournament = new Tournament('Invalid Tournament', 'M');
        $tournament->setId($tournamentId);

        $player1 = new Player('Player 1', 80, 'M', 70, 75);
        $player1->setId(1);
        $player2 = new Player('Player 2', 75, 'M', 65, 80);
        $player2->setId(2);

        $this->tournamentRepository->expects($this->once())
            ->method('findById')
            ->with($tournamentId)
            ->willReturn($tournament);

        $this->matchupRepository->expects($this->once())
            ->method('findByTournamentId')
            ->with($tournamentId, false)
            ->willReturn([new Matchup($player1, $player2, $tournament)]);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Invalid number of matchups');

        $this->useCase->execute($tournamentId);
    }
}
