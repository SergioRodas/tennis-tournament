<?php

namespace Tests\Unit\Api\Player\Application\UseCases;

use App\Api\Player\Application\UseCases\ListPlayersUseCase;
use App\Api\Player\Domain\Player;
use App\Api\Player\Domain\PlayerRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;

class ListPlayersUseCaseTest extends TestCase
{
    /** @var PlayerRepository&MockObject */
    private $repository;

    private ValidatorInterface $validator;
    private ListPlayersUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(PlayerRepository::class);
        $this->validator = Validation::createValidator();
        $this->useCase = new ListPlayersUseCase($this->repository, $this->validator);
    }

    public function testListPlayersWithDefaultParams()
    {
        $players = [
            new Player('John Doe', 80, 'M', 70, 75),
            new Player('Jane Doe', 85, 'F', null, null, 0.5),
        ];

        $this->repository->expects($this->once())
            ->method('findAllWithFilters')
            ->with(
                $this->equalTo(['skill' => null, 'gender' => null]),
                $this->equalTo(1),
                $this->equalTo(20)
            )
            ->willReturn($players);

        $this->repository->expects($this->once())
            ->method('countAllWithFilters')
            ->willReturn(2);

        $result = $this->useCase->execute([]);

        $this->assertArrayHasKey('players', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertCount(2, $result['players']);
        $this->assertInstanceOf(Player::class, $result['players'][0]);
        $this->assertInstanceOf(Player::class, $result['players'][1]);
        $this->assertEquals(2, $result['pagination']['totalItems']);
        $this->assertEquals(20, $result['pagination']['itemsPerPage']);
        $this->assertEquals(1, $result['pagination']['currentPage']);
        $this->assertEquals(1, $result['pagination']['totalPages']);
    }

    public function testListPlayersWithFilters()
    {
        $players = [
            new Player('John Doe', 80, 'M', 70, 75),
        ];

        $this->repository->expects($this->once())
            ->method('findAllWithFilters')
            ->with(
                $this->equalTo(['skill' => 80, 'gender' => 'M']),
                $this->equalTo(2),
                $this->equalTo(10)
            )
            ->willReturn($players);

        $this->repository->expects($this->once())
            ->method('countAllWithFilters')
            ->willReturn(1);

        $result = $this->useCase->execute([
            'page' => 2,
            'limit' => 10,
            'skill' => 80,
            'gender' => 'm',
        ]);

        $this->assertCount(1, $result['players']);
        $this->assertInstanceOf(Player::class, $result['players'][0]);
        $this->assertEquals('M', $result['players'][0]->getGender());
        $this->assertEquals(80, $result['players'][0]->getSkillLevel());
    }

    public function testListPlayersWithInvalidParams()
    {
        $result = $this->useCase->execute([
            'page' => 0,  // Invalid page number
            'limit' => 1000,  // Exceeds maximum limit
        ]);

        $this->assertArrayHasKey('players', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertEmpty($result['players']);
        $this->assertEquals(0, $result['pagination']['totalItems']);
        $this->assertEquals(1, $result['pagination']['currentPage']);
        $this->assertEquals(20, $result['pagination']['itemsPerPage']);
        $this->assertEquals(1, $result['pagination']['totalPages']);
    }

    public function testListPlayersNoResults()
    {
        $this->repository->expects($this->once())
            ->method('findAllWithFilters')
            ->willReturn([]);

        $result = $this->useCase->execute([]);

        $this->assertArrayHasKey('players', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertEmpty($result['players']);
        $this->assertEquals(0, $result['pagination']['totalItems']);
        $this->assertEquals(1, $result['pagination']['currentPage']);
        $this->assertEquals(20, $result['pagination']['itemsPerPage']);
        $this->assertEquals(1, $result['pagination']['totalPages']);
    }
}
