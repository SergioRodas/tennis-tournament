<?php

namespace App\Api\Matchup\Application\UseCases;

use App\Api\Matchup\Application\Dto\CreateMatchupRequestDto;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\PlayerRepository;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateMatchupUseCase
{
    private MatchupRepository $matchupRepository;
    private PlayerRepository $playerRepository;
    private TournamentRepository $tournamentRepository;
    private ValidatorInterface $validator;

    public function __construct(
        MatchupRepository $matchupRepository,
        PlayerRepository $playerRepository,
        TournamentRepository $tournamentRepository,
        ValidatorInterface $validator
    ) {
        $this->matchupRepository = $matchupRepository;
        $this->playerRepository = $playerRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->validator = $validator;
    }

    public function execute(array $data): void
    {
        $dto = new CreateMatchupRequestDto(
            (int) $data['player1_id'],
            (int) $data['player2_id'],
            (int) $data['tournament_id']
        );

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw new ApiException(json_encode(['errors' => $errorMessages]), Response::HTTP_BAD_REQUEST);
        }

        $player1 = $this->playerRepository->findById($dto->player1Id);
        $player2 = $this->playerRepository->findById($dto->player2Id);
        $tournament = $this->tournamentRepository->findById($dto->tournamentId);

        if (!$player1 || !$player2 || !$tournament) {
            throw new ApiException('Invalid players or tournament', Response::HTTP_NOT_FOUND);
        }

        if (!$tournament->canPlayersParticipate($player1, $player2)) {
            throw new ApiException('Players do not meet the tournament requirements', Response::HTTP_BAD_REQUEST);
        }

        $this->matchupRepository->save($dto->player1Id, $dto->player2Id, $dto->tournamentId);
    }
}
