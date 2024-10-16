<?php

namespace Tests\Unit\Api\Tournament\Application\UseCases;

use App\Api\Tournament\Application\UseCases\GetTournamentUseCase;
use App\Api\Tournament\Domain\Tournament;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class GetTournamentUseCaseTest extends TestCase
{
    /** @var TournamentRepository&MockObject */
    private $tournamentRepository;
    private GetTournamentUseCase $useCase;

    protected function setUp(): void
    {
        $this->tournamentRepository = $this->createMock(TournamentRepository::class);
        $this->useCase = new GetTournamentUseCase($this->tournamentRepository);
    }

    public function testGetTournamentSuccess()
    {
        $tournamentId = 1;
        $tournament = new Tournament('Test Tournament', 'M');
        $tournament->setId($tournamentId);

        $this->tournamentRepository->expects($this->once())
            ->method('findById')
            ->with($tournamentId)
            ->willReturn($tournament);

        $result = $this->useCase->execute($tournamentId);

        $this->assertInstanceOf(Tournament::class, $result);
        $this->assertEquals($tournamentId, $result->getId());
        $this->assertEquals('Test Tournament', $result->getName());
        $this->assertEquals('M', $result->getGender());
    }

    public function testGetTournamentNotFound()
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
}
