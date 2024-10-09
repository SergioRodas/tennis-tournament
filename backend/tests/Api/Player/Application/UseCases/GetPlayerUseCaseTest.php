<?php

namespace Tests\Api\Player\Application\UseCases;

use App\Api\Player\Application\UseCases\GetPlayerUseCase;
use App\Api\Player\Domain\Player;
use App\Api\Player\Domain\PlayerRepository;
use App\Shared\Domain\Exception\ApiException;
use PHPUnit\Framework\TestCase;

class GetPlayerUseCaseTest extends TestCase
{
    private $repository;
    private GetPlayerUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = $this->getMockBuilder(PlayerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->useCase = new GetPlayerUseCase($this->repository);
    }

    public function testGetExistingPlayer()
    {
        $playerId = 1;
        $player = new Player('John Doe', 80, 'M', 70, 75);
        $player->setId($playerId);

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($playerId)
            ->willReturn($player);

        $result = $this->useCase->execute($playerId);

        $this->assertInstanceOf(Player::class, $result);
        $this->assertEquals($playerId, $result->getId());
        $this->assertEquals('John Doe', $result->getName());
    }

    public function testGetNonExistingPlayer()
    {
        $playerId = 999;

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($playerId)
            ->willReturn(null);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Player not found');

        $this->useCase->execute($playerId);
    }
}
