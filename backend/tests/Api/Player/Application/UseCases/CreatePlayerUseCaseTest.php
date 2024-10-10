<?php

namespace Tests\Api\Player\Application\UseCases;

use App\Api\Player\Application\UseCases\CreatePlayerUseCase;
use App\Api\Player\Domain\Player;
use App\Api\Player\Domain\PlayerRepository;
use App\Shared\Domain\Exception\ApiException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreatePlayerUseCaseTest extends TestCase
{
    private $repository;
    private ValidatorInterface $validator;
    private CreatePlayerUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = $this->getMockBuilder(PlayerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->validator = Validation::createValidator();
        $this->useCase = new CreatePlayerUseCase($this->repository, $this->validator);
    }

    public function testCreateMalePlayer()
    {
        $data = [
            'name' => 'John Doe',
            'skillLevel' => 80,
            'gender' => 'M',
            'strength' => 70,
            'speed' => 75,
        ];

        $this->repository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Player $player) {
                $player->setId(1);

                return $player;
            });

        $player = $this->useCase->execute($data);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertEquals('John Doe', $player->getName());
        $this->assertEquals(80, $player->getSkillLevel());
        $this->assertEquals('M', $player->getGender());
        $this->assertEquals(70, $player->getStrength());
        $this->assertEquals(75, $player->getSpeed());
        $this->assertNull($player->getReactionTime());
    }

    public function testCreateFemalePlayer()
    {
        $data = [
            'name' => 'Jane Doe',
            'skillLevel' => 85,
            'gender' => 'f',
            'reactionTime' => 0.5,
        ];

        $this->repository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Player $player) {
                $player->setId(2);

                return $player;
            });

        $player = $this->useCase->execute($data);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertEquals('Jane Doe', $player->getName());
        $this->assertEquals(85, $player->getSkillLevel());
        $this->assertEquals('F', $player->getGender());
        $this->assertNull($player->getStrength());
        $this->assertNull($player->getSpeed());
        $this->assertEquals(0.5, $player->getReactionTime());
    }

    public function testInvalidData()
    {
        $data = [
            'name' => 'Invalid Player',
            'skillLevel' => 150, // Invalid skill level
            'gender' => 'X', // Invalid gender
        ];

        $this->expectException(ApiException::class);

        try {
            $this->useCase->execute($data);
        } catch (ApiException $e) {
            $errorMessage = $e->getMessage();
            $this->assertThat(
                $errorMessage,
                $this->logicalOr(
                    $this->stringContains('Skill level must be between 0 and 100'),
                    $this->stringContains('Gender must be either M or F')
                )
            );
            throw $e;
        }
    }
}
