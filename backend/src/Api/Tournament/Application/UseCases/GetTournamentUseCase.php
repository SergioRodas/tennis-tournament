<?php

namespace App\Api\Tournament\Application\UseCases;

use App\Api\Tournament\Domain\Tournament;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class GetTournamentUseCase
{
    public function __construct(private TournamentRepository $tournamentRepository)
    {
    }

    public function execute(int $id): Tournament
    {
        $tournament = $this->tournamentRepository->findById($id);

        if (!$tournament) {
            throw new ApiException('Tournament not found', Response::HTTP_NOT_FOUND);
        }

        return $tournament;
    }
}
