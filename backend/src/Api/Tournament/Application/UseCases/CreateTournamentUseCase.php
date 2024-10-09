<?php

namespace App\Api\Tournament\Application\UseCases;

use App\Api\Tournament\Application\Dto\CreateTournamentRequestDto;
use App\Api\Tournament\Domain\Tournament;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateTournamentUseCase
{
    private TournamentRepository $tournamentRepository;
    private ValidatorInterface $validator;

    public function __construct(TournamentRepository $tournamentRepository, ValidatorInterface $validator)
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->validator = $validator;
    }

    public function execute(array $data): Tournament
    {
        $gender = $data['gender'] ?? null;

        if (null === $gender) {
            throw new ApiException('This value should not be blank.', Response::HTTP_BAD_REQUEST);
        }

        $gender = strtoupper($gender);

        if (!in_array($gender, ['M', 'F'])) {
            throw new ApiException('Invalid gender. Allowed values are \'M\' or \'F\'.', Response::HTTP_BAD_REQUEST);
        }

        $dto = new CreateTournamentRequestDto($gender);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw new ApiException($errorMessages[0], Response::HTTP_BAD_REQUEST);
        }

        try {
            $tournament = new Tournament($gender);
            $this->tournamentRepository->save($tournament);

            return $tournament;
        } catch (\Exception $e) {
            throw new ApiException('Failed to create tournament: '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
