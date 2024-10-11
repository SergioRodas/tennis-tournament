<?php

namespace App\Tests\Integration\Api\Tournament;

use App\Tests\Integration\Shared\Infrastructure\IntegrationTestCase;
use App\Api\Tournament\Application\UseCases\CreateTournamentUseCase;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;

class CreateTournamentIntegrationTest extends IntegrationTestCase
{
    private CreateTournamentUseCase $createTournamentUseCase;
    private TournamentRepository $tournamentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTournamentUseCase = self::getContainer()->get(CreateTournamentUseCase::class);
        $this->tournamentRepository = self::getContainer()->get(TournamentRepository::class);
    }

    public function testCreateTournament(): void
    {
        $tournamentData = ['name' => 'Test Tournament', 'gender' => 'M'];

        $tournament = $this->createTournamentUseCase->execute($tournamentData);

        $this->assertNotNull($tournament->getId());
        $this->assertEquals('Test Tournament', $tournament->getName());
        $this->assertEquals('M', $tournament->getGender());
    }

    public function testCreateTournamentWithInvalidGender(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Invalid gender. Allowed values are \'M\' or \'F\'.');

        $tournamentData = ['name' => 'Test Tournament', 'gender' => 'X'];
        $this->createTournamentUseCase->execute($tournamentData);
    }

    public function testCreateTournamentWithoutName(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Name and gender are required.');

        $tournamentData = ['gender' => 'M'];
        $this->createTournamentUseCase->execute($tournamentData);
    }
}
