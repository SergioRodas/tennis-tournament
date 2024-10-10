<?php

namespace App\Tests\Integration\Api\Tournament;

use App\Tests\Integration\Shared\Infrastructure\IntegrationTestCase;
use App\Api\Tournament\Application\UseCases\GetTournamentUseCase;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Api\Tournament\Domain\Tournament;
use App\Shared\Domain\Exception\ApiException;

class GetTournamentIntegrationTest extends IntegrationTestCase
{
    private GetTournamentUseCase $getTournamentUseCase;
    private TournamentRepository $tournamentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getTournamentUseCase = self::getContainer()->get(GetTournamentUseCase::class);
        $this->tournamentRepository = self::getContainer()->get(TournamentRepository::class);
    }

    public function testGetExistingTournament(): void
    {
        $tournament = new Tournament('M');
        $this->tournamentRepository->save($tournament);

        $retrievedTournament = $this->getTournamentUseCase->execute($tournament->getId());

        $this->assertInstanceOf(Tournament::class, $retrievedTournament);
        $this->assertEquals($tournament->getId(), $retrievedTournament->getId());
        $this->assertEquals('M', $retrievedTournament->getGender());
    }

    public function testGetNonExistingTournament(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Tournament not found');

        $this->getTournamentUseCase->execute(999);
    }
}
