<?php

namespace Tests\Unit\Api\Matchup\Application\UseCases;

use App\Api\Matchup\Application\UseCases\CreateMatchupUseCase;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\Player;
use App\Api\Player\Domain\PlayerRepository;
use App\Api\Tournament\Domain\Tournament;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;

class CreateMatchupUseCaseTest extends TestCase
{
    /** @var MatchupRepository&MockObject */
    private $matchupRepository;

    /** @var PlayerRepository&MockObject */
    private $playerRepository;

    /** @var TournamentRepository&MockObject */
    private $tournamentRepository;

    private ValidatorInterface $validator;
    private CreateMatchupUseCase $useCase;

    protected function setUp(): void
    {
        $this->matchupRepository = $this->createMock(MatchupRepository::class);
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        $this->tournamentRepository = $this->createMock(TournamentRepository::class);
        $this->validator = Validation::createValidator();
        $this->useCase = new CreateMatchupUseCase(
            $this->matchupRepository,
            $this->playerRepository,
            $this->tournamentRepository,
            $this->validator
        );
    }

    public function testCreateMatchupSuccess()
    {
        $player1 = new Player('John Doe', 80, 'M', 70, 75);
        $player1->setId(1);
        $player2 = new Player('Jane Doe', 85, 'F', null, null, 0.5);
        $player2->setId(2);
        $tournament = $this->createMock(Tournament::class);
        $tournament->method('canPlayersParticipate')->willReturn(true);

        $this->playerRepository->method('findById')
            ->willReturnMap([
                [1, $player1],
                [2, $player2],
            ]);
        $this->tournamentRepository->method('findById')->willReturn($tournament);
        $this->matchupRepository->expects($this->once())->method('save');

        $data = [
            'player1_id' => 1,
            'player2_id' => 2,
            'tournament_id' => 1,
        ];

        $this->useCase->execute($data);
    }

    public function testCreateMatchupInvalidData()
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Invalid players or tournament');

        $data = [
            'player1_id' => 'invalid',
            'player2_id' => 2,
            'tournament_id' => 1,
        ];

        $this->useCase->execute($data);
    }

    public function testCreateMatchupPlayerNotFound()
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Invalid players or tournament');

        $this->playerRepository->method('findById')->willReturn(null);

        $data = [
            'player1_id' => 1,
            'player2_id' => 2,
            'tournament_id' => 1,
        ];

        $this->useCase->execute($data);
    }

    public function testCreateMatchupPlayersNotEligible()
    {
        $player1 = new Player('John Doe', 80, 'M', 70, 75);
        $player2 = new Player('Jane Doe', 85, 'F', null, null, 0.5);
        $tournament = $this->createMock(Tournament::class);
        $tournament->method('canPlayersParticipate')->willReturn(false);

        $this->playerRepository->method('findById')->willReturnOnConsecutiveCalls($player1, $player2);
        $this->tournamentRepository->method('findById')->willReturn($tournament);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Players do not meet the tournament requirements');

        $data = [
            'player1_id' => 1,
            'player2_id' => 2,
            'tournament_id' => 1,
        ];

        $this->useCase->execute($data);
    }
}
