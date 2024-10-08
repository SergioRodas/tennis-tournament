<?php

namespace App\Api\Tournament\Application\UseCases;

use App\Api\Tournament\Domain\Tournament;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class CreateTournamentUseCase
{
    private TournamentRepository $tournamentRepository;

    public function __construct(TournamentRepository $tournamentRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
    }

    public function execute($gender): Tournament
    {
        try {
            $tournament = new Tournament($gender);
            $this->tournamentRepository->save($tournament);

            return $tournament;
        } catch (\Exception $e) {
            throw new ApiException('Failed to create tournament: '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
