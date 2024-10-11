<?php

namespace Tests\Unit\Api\Tournament\Application\UseCases;

use App\Api\Tournament\Application\UseCases\CreateTournamentUseCase;
use App\Api\Tournament\Domain\Tournament;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;

class CreateTournamentUseCaseTest extends TestCase
{
    /** @var TournamentRepository&MockObject */
    private $tournamentRepository;
    private ValidatorInterface $validator;
    private CreateTournamentUseCase $useCase;

    protected function setUp(): void
    {
        $this->tournamentRepository = $this->createMock(TournamentRepository::class);
        $this->validator = Validation::createValidator();
        $this->useCase = new CreateTournamentUseCase($this->tournamentRepository, $this->validator);
    }

    public function testCreateTournamentSuccess()
    {
        $data = ['name' => 'Test Tournament', 'gender' => 'M'];

        $this->tournamentRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Tournament $tournament) {
                $tournament->setId(1);

                return $tournament;
            });

        $result = $this->useCase->execute($data);

        $this->assertInstanceOf(Tournament::class, $result);
        $this->assertEquals('Test Tournament', $result->getName());
        $this->assertEquals('M', $result->getGender());
        $this->assertEquals(1, $result->getId());
    }

    public function testCreateTournamentInvalidGender()
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Invalid gender. Allowed values are \'M\' or \'F\'.');

        $data = [
            'name' => 'Test Tournament',
            'gender' => 'X', // Género inválido
        ];

        $this->useCase->execute($data);
    }

    public function testCreateTournamentMissingName()
    {
        $data = ['gender' => 'M'];

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Name and gender are required.');

        $this->useCase->execute($data);
    }

    public function testCreateTournamentMissingGender()
    {
        $data = ['name' => 'Test Tournament'];

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Name and gender are required.');

        $this->useCase->execute($data);
    }

    public function testCreateTournamentRepositoryException()
    {
        $data = ['name' => 'Test Tournament', 'gender' => 'M'];

        $this->tournamentRepository->expects($this->once())
            ->method('save')
            ->willThrowException(new \Exception('Database error'));

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Failed to create tournament: Database error');

        $this->useCase->execute($data);
    }
}
