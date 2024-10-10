<?php

namespace Tests\Unit\Api\Matchup\Application\UseCases;

use App\Api\Matchup\Application\UseCases\UpdateMatchupWinnerUseCase;
use App\Api\Matchup\Domain\Matchup;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\Player;
use App\Api\Player\Domain\PlayerRepository;
use App\Shared\Domain\Exception\ApiException;
use PHPUnit\Framework\TestCase;

class UpdateMatchupWinnerUseCaseTest extends TestCase
{
    private $matchupRepository;
    private $playerRepository;
    private UpdateMatchupWinnerUseCase $useCase;

    protected function setUp(): void
    {
        $this->matchupRepository = $this->createMock(MatchupRepository::class);
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        $this->useCase = new UpdateMatchupWinnerUseCase(
            $this->matchupRepository,
            $this->playerRepository
        );
    }

    public function testUpdateMatchupWinnerSuccess()
    {
        $matchupId = 1;
        $winnerId = 2;
        $matchup = $this->createMock(Matchup::class);
        $winner = $this->createMock(Player::class);

        $this->matchupRepository->expects($this->once())
            ->method('findById')
            ->with($matchupId)
            ->willReturn($matchup);

        $this->playerRepository->expects($this->once())
            ->method('findById')
            ->with($winnerId)
            ->willReturn($winner);

        $this->matchupRepository->expects($this->once())
            ->method('updateWinner')
            ->with($matchupId, $winnerId);

        $this->useCase->execute($matchupId, $winnerId);
    }

    public function testUpdateMatchupWinnerMatchupNotFound()
    {
        $matchupId = 999;
        $winnerId = 1;

        $this->matchupRepository->expects($this->once())
            ->method('findById')
            ->with($matchupId)
            ->willReturn(null);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Matchup not found');

        $this->useCase->execute($matchupId, $winnerId);
    }

    public function testUpdateMatchupWinnerPlayerNotFound()
    {
        $matchupId = 1;
        $winnerId = 999;
        $matchup = $this->createMock(Matchup::class);

        $this->matchupRepository->expects($this->once())
            ->method('findById')
            ->with($matchupId)
            ->willReturn($matchup);

        $this->playerRepository->expects($this->once())
            ->method('findById')
            ->with($winnerId)
            ->willReturn(null);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Winner not found');

        $this->useCase->execute($matchupId, $winnerId);
    }
}
