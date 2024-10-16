<?php

namespace App\Tests\Integration\Api\Tournament;

use App\Tests\Integration\Shared\Infrastructure\IntegrationTestCase;
use App\Api\Tournament\Application\UseCases\ListTournamentsUseCase;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Api\Tournament\Domain\Tournament;
use App\Shared\Domain\Exception\ApiException;

class ListTournamentsIntegrationTest extends IntegrationTestCase
{
    private ListTournamentsUseCase $listTournamentsUseCase;
    private TournamentRepository $tournamentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->listTournamentsUseCase = self::getContainer()->get(ListTournamentsUseCase::class);
        $this->tournamentRepository = self::getContainer()->get(TournamentRepository::class);
    }

    public function testListTournaments(): void
    {
        $tournament1 = new Tournament('Tournament 1', 'M');
        $tournament2 = new Tournament('Tournament 2', 'F');
        $this->tournamentRepository->save($tournament1);
        $this->tournamentRepository->save($tournament2);

        $tournaments = $this->listTournamentsUseCase->execute();

        $this->assertCount(2, $tournaments);
        $this->assertInstanceOf(Tournament::class, $tournaments[0]);
        $this->assertInstanceOf(Tournament::class, $tournaments[1]);
        $this->assertEquals('Tournament 1', $tournaments[0]->getName());
        $this->assertEquals('Tournament 2', $tournaments[1]->getName());
    }

    public function testListTournamentsWithGenderFilter(): void
    {
        $tournament1 = new Tournament('Tournament 1', 'M');
        $tournament2 = new Tournament('Tournament 2', 'F');
        $this->tournamentRepository->save($tournament1);
        $this->tournamentRepository->save($tournament2);

        $tournaments = $this->listTournamentsUseCase->execute(['gender' => 'M']);

        $this->assertCount(1, $tournaments);
        $this->assertEquals('M', $tournaments[0]->getGender());
        $this->assertEquals('Tournament 1', $tournaments[0]->getName());
    }

    public function testListTournamentsWithDateFilter(): void
    {
        $tournament1 = new Tournament('Tournament 1', 'M');
        $this->tournamentRepository->save($tournament1);

        sleep(1);

        $filterDate = new \DateTime();

        sleep(1);

        $tournament2 = new Tournament('Tournament 2', 'F');
        $this->tournamentRepository->save($tournament2);

        $tournaments = $this->listTournamentsUseCase->execute(['createdAt' => $filterDate], 0, 20, 'createdAt', 'asc');

        $this->assertCount(1, $tournaments);
        $this->assertEquals('F', $tournaments[0]->getGender());
        $this->assertEquals('Tournament 2', $tournaments[0]->getName());
    }

    public function testListTournamentsWithNoResults(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('No tournaments found');

        $this->listTournamentsUseCase->execute();
    }
}
