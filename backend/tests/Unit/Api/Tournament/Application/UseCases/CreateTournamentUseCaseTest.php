<?php

namespace Tests\Unit\Api\Tournament\Application\UseCases;

use App\Api\Tournament\Application\UseCases\CreateTournamentUseCase;
use App\Api\Tournament\Domain\Tournament;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateTournamentUseCaseTest extends TestCase
{
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
        $data = ['gender' => 'M'];

        $this->tournamentRepository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Tournament $tournament) {
                $tournament->setId(1);

                return $tournament;
            });

        $result = $this->useCase->execute($data);

        $this->assertInstanceOf(Tournament::class, $result);
        $this->assertEquals('M', $result->getGender());
        $this->assertEquals(1, $result->getId());
    }

    public function testCreateTournamentInvalidGender()
    {
        $data = ['gender' => 'X'];

        try {
            $this->useCase->execute($data);
            $this->fail('Expected exception was not thrown');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ApiException::class, $e, 'Unexpected exception: '.get_class($e).' with message: '.$e->getMessage());
            $this->assertEquals('Invalid gender. Allowed values are \'M\' or \'F\'.', $e->getMessage());
        }
    }

    public function testCreateTournamentMissingGender()
    {
        $data = [];

        try {
            $this->useCase->execute($data);
            $this->fail('Expected exception was not thrown');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ApiException::class, $e, 'Unexpected exception: '.get_class($e).' with message: '.$e->getMessage());
            $this->assertEquals('This value should not be blank.', $e->getMessage());
        }
    }

    public function testCreateTournamentRepositoryException()
    {
        $data = ['gender' => 'M'];

        $this->tournamentRepository->expects($this->once())
            ->method('save')
            ->willThrowException(new \Exception('Database error'));

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Failed to create tournament: Database error');

        $this->useCase->execute($data);
    }
}
