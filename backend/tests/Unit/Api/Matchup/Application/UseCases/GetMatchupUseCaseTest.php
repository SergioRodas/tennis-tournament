<?php

namespace Tests\Unit\Api\Matchup\Application\UseCases;

use App\Api\Matchup\Application\UseCases\GetMatchupUseCase;
use App\Api\Matchup\Domain\Matchup;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\Player;
use App\Api\Tournament\Domain\Tournament;
use App\Shared\Domain\Exception\ApiException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class GetMatchupUseCaseTest extends TestCase
{
    /** @var MatchupRepository&MockObject */
    private $matchupRepository;
    private GetMatchupUseCase $useCase;

    protected function setUp(): void
    {
        $this->matchupRepository = $this->createMock(MatchupRepository::class);
        $this->useCase = new GetMatchupUseCase($this->matchupRepository);
    }

    public function testGetExistingMatchup()
    {
        $matchupId = 1;
        $player1 = new Player('John Doe', 80, 'M', 70, 75);
        $player2 = new Player('Jane Doe', 85, 'F', null, null, 0.5);
        /** @var Tournament&MockObject */
        $tournament = $this->createMock(Tournament::class);
        $matchup = new Matchup($player1, $player2, $tournament);
        $matchup->setId($matchupId);

        $this->matchupRepository->expects($this->once())
            ->method('findById')
            ->with($matchupId)
            ->willReturn($matchup);

        $result = $this->useCase->execute($matchupId);

        $this->assertInstanceOf(Matchup::class, $result);
        $this->assertEquals($matchupId, $result->getId());
        $this->assertEquals($player1, $result->getPlayer1());
        $this->assertEquals($player2, $result->getPlayer2());
    }

    public function testGetNonExistingMatchup()
    {
        $matchupId = 999;

        $this->matchupRepository->expects($this->once())
            ->method('findById')
            ->with($matchupId)
            ->willReturn(null);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Matchup not found');

        $this->useCase->execute($matchupId);
    }
}
