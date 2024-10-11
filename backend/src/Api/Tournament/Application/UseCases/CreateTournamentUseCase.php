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
        $name = $data['name'] ?? null;
        $gender = $data['gender'] ?? null;

        if (null === $name || null === $gender) {
            throw new ApiException('Name and gender are required.', Response::HTTP_BAD_REQUEST);
        }

        $gender = strtoupper($gender);

        $dto = new CreateTournamentRequestDto($name, $gender);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw new ApiException($errorMessages[0], Response::HTTP_BAD_REQUEST);
        }

        try {
            $tournament = new Tournament($name, $gender);
            $this->tournamentRepository->save($tournament);

            return $tournament;
        } catch (\Exception $e) {
            throw new ApiException('Failed to create tournament: '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
