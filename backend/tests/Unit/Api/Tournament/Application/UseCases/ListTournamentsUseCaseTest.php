<?php

namespace Tests\Unit\Api\Tournament\Application\UseCases;

use App\Api\Tournament\Application\UseCases\ListTournamentsUseCase;
use App\Api\Tournament\Domain\Tournament;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use PHPUnit\Framework\TestCase;

class ListTournamentsUseCaseTest extends TestCase
{
    private $tournamentRepository;
    private ListTournamentsUseCase $useCase;

    protected function setUp(): void
    {
        $this->tournamentRepository = $this->createMock(TournamentRepository::class);
        $this->useCase = new ListTournamentsUseCase($this->tournamentRepository);
    }

    public function testListTournamentsSuccess()
    {
        $filters = ['gender' => 'M'];
        $offset = 0;
        $limit = 20;
        $orderBy = 'createdAt';
        $order = 'asc';

        $expectedFilters = [
            'gender' => 'M',
            'orderBy' => 'createdAt',
            'order' => 'asc',
        ];

        $tournaments = [
            new Tournament('M'),
            new Tournament('M'),
        ];

        $this->tournamentRepository->expects($this->once())
            ->method('findByFilters')
            ->with($expectedFilters, $offset, $limit)
            ->willReturn($tournaments);

        $result = $this->useCase->execute($filters, $offset, $limit, $orderBy, $order);

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(Tournament::class, $result);
    }

    public function testListTournamentsNoResults()
    {
        $filters = ['gender' => 'F'];
        $offset = 0;
        $limit = 20;
        $orderBy = 'createdAt';
        $order = 'asc';

        $expectedFilters = [
            'gender' => 'F',
            'orderBy' => 'createdAt',
            'order' => 'asc',
        ];

        $this->tournamentRepository->expects($this->once())
            ->method('findByFilters')
            ->with($expectedFilters, $offset, $limit)
            ->willReturn([]);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('No tournaments found');

        $this->useCase->execute($filters, $offset, $limit, $orderBy, $order);
    }

    public function testListTournamentsWithDefaultParameters()
    {
        $expectedFilters = [
            'orderBy' => 'createdAt',
            'order' => 'asc',
        ];

        $tournaments = [new Tournament('M')];

        $this->tournamentRepository->expects($this->once())
            ->method('findByFilters')
            ->with($expectedFilters, 0, 20)
            ->willReturn($tournaments);

        $result = $this->useCase->execute();

        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(Tournament::class, $result);
    }

    public function testListTournamentsWithCustomOrderingParameters()
    {
        $filters = ['gender' => 'M'];
        $offset = 0;
        $limit = 10;
        $orderBy = 'gender';
        $order = 'desc';

        $expectedFilters = [
            'gender' => 'M',
            'orderBy' => 'gender',
            'order' => 'desc',
        ];

        $tournaments = [new Tournament('M'), new Tournament('M')];

        $this->tournamentRepository->expects($this->once())
            ->method('findByFilters')
            ->with($expectedFilters, $offset, $limit)
            ->willReturn($tournaments);

        $result = $this->useCase->execute($filters, $offset, $limit, $orderBy, $order);

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(Tournament::class, $result);
    }
}
